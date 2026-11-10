<template>
    <el-upload
            ref="excel"
            action=""
            :limit="1"
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
            :show-file-list="false"
            :before-upload="beforeUpload"
            :http-request="uploadFile">
        <el-button :size="buttonSize" type="success" icon="el-icon-upload2">{{buttonTitle}}</el-button>
    </el-upload>
</template>

<script>
    import excel from '@/libs/excel'
    import { showLoading, hideLoading } from '@/libs/loading'

    export default {
        name: "ExcelImport",
        data(){
            return {
                file: null,
                fileList:[]
            }
        },
        props:{
            excelFormat:Array,
            buttonTitle:{
                type:String,
                default:'Excel导入'
            },
            buttonSize:{
                type:String,
                default:'small'
            }
        },
        methods:{
            beforeUpload(file){
                const file_type = file.name.split('.').pop().toLocaleLowerCase()
                const file_size = file.size / 1024 / 1024
                if (file_type=== 'xlsx' || file_type === 'xls') {
                    if (file_size>10) {
                        this.$message.error('上传文件大小不能超过10MB!');
                    }else{
                        this.readFile(file)
                        this.file = file
                        return true
                    }
                }else{
                    this.$message.error('上传文件不是EXCEL文件，请选择后缀为.xlsx或者.xls的EXCEL文件!')
                }
                return false
            },
            readFile(file){
                const reader = new FileReader()
                reader.readAsArrayBuffer(file)
                reader.onloadstart = () => {
                    showLoading()
                }
                reader.onprogress = e => {
                    this.progressPercent = Math.round(e.loaded / e.total * 100)
                }
                reader.onerror = () => {
                    this.$message.error('文件读取出错')
                }
                reader.onload = e => {
                    hideLoading()
                    const data = e.target.result
                    const { results } = excel.read(data, 'array')
                    let outdata = this.formatData(results)
                    this.$refs.excel.clearFiles()
                    this.$emit('catchExcelData',outdata)
                }
            },
            formatData(tableData){
                let importData = []
                for(let item of tableData) {
                    let obj = {}
                    for(let key in item) {
                        for(let childItem of this.excelFormat) {
                            if(key === childItem.label) {
                                obj[childItem.value] = item[key]
                                break
                            }
                        }
                    }
                    importData.push(obj)
                }
                return importData
            },
            uploadFile(){
                this.file = null
            }
        }
    }
</script>