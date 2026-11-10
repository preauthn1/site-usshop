import {isEmpty} from '@/libs/util'

export default {
    components:{},
    data(){
        return {
            apiName:'',
            chartData:[],
            dtrange:[],
            queryForm:{
                start_time:'',
                end_time:''
            },
            pickerOptions: {
                shortcuts: [{
                    text: '最近一周',
                    onClick(picker) {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                        picker.$emit('pick', [start, end])
                    }
                }, {
                    text: '最近一个月',
                    onClick(picker) {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                        picker.$emit('pick', [start, end])
                    }
                }, {
                    text: '最近三个月',
                    onClick(picker) {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
                        picker.$emit('pick', [start, end])
                    }
                }]
            }
        }
    },
    methods:{
        async beforeGetData(){},
        afterGetData(){},
        getChartData(){
            const self = this
            self.beforeGetData()
            self.$api.analysis[self.apiName](self.queryForm).then(response => {
                if(response.code==1){
                    if(response.hasOwnProperty('data')){
                        self.chartData = response.data
                    }else{
                        this.chartData = []
                    }
                    self.afterGetData()
                }
            })
        },
        handleDt(val){
            if(!isEmpty(val)){
                this.queryForm.start_time = val[0]
                this.queryForm.end_time = val[1]
                this.getChartData()
            }
        }
    },
    created() {
        const end_time = new Date()
        const start_time = new Date()
        start_time.setTime(start_time.getTime() - 3600 * 1000 * 24 * 7)
        this.dtrange = [start_time,end_time]
        this.queryForm.start_time = this.$moment(start_time).format('YYYY-MM-DD')
        this.queryForm.end_time = this.$moment(end_time).format('YYYY-MM-DD')
        this.getChartData()
    }
}