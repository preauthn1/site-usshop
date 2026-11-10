<template>
    <div>
        <t-editor :id="Id" :init="DefaultConfig"></t-editor>
    </div>
</template>

<script>
    import oss from '@/libs/oss'
    import tinymce from 'tinymce/tinymce'
    import TEditor from '@tinymce/tinymce-vue'
    import 'tinymce/themes/silver/theme'
    import 'tinymce/plugins/image'
    import 'tinymce/plugins/media'
    import 'tinymce/plugins/link'
    import 'tinymce/plugins/code'
    import 'tinymce/plugins/table'
    import 'tinymce/plugins/lists'
    import 'tinymce/plugins/wordcount'
    import 'tinymce/plugins/preview'
    import 'tinymce/plugins/paste'
    import 'tinymce/plugins/code'
    import 'tinymce/plugins/fullscreen'
    import 'tinymce/skins/ui/oxide/skin.css'

    export default {
        components: {
            TEditor
        },
        name: "EditorTinymce",
        props: {
            value: {
                type: String,
                default: ''
            },
            accept: {
                type: String,
                default: 'image/png,image/jpge,image/jpg'
            },
            maxsize: {
                type: Number,
                default: 2097152
            },
            plugins: {
                type: [String, Array],
                default: 'link lists image media table wordcount preview code fullscreen'
            },
            toolbar: {
                type: [String, Array],
                default: 'fullscreen preview code | fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent blockquote | link unlink image media code | undo redo removeformat'
            }
        },
        data(){
            const self = this
            const Id = Date.now()+''
            return {
                Id:Id,
                DefaultConfig:{
                    language_url: '/tinymce/langs/zh_CN.js',
                    language: 'zh_CN',
                    skin_url: '/tinymce/skins/ui/oxide',
                    height: 300,
                    plugins: this.plugins,
                    toolbar: this.toolbar,
                    branding: false,
                    menubar: true,
                    forced_root_block: 'p',
                    force_p_newlines: true,
                    importcss_append: true,
                    content_style: `
                        *                         { padding:0; margin:0; }
                        html, body                { height:100%; }
                        img                       { max-width:100%; display:block;height:auto; }
                        a                         { text-decoration: none; }
                        iframe                    { width: 100%; }
                        p                         { line-height:1.6; margin: 0px; }
                        table                     { word-wrap:break-word; word-break:break-all; max-width:100%; border:none; border-color:#999; }
                        .mce-object-iframe        { width:100%; box-sizing:border-box; margin:0; padding:0; }
                        ul,ol                     { list-style-position:inside; }
                    `,
                    insert_button_items: 'image link | inserttable',
                    paste_retain_style_properties: 'all',
                    paste_word_valid_elements: '*[*]',
                    paste_data_images: true,
                    paste_convert_word_fake_lists: false,
                    paste_webkit_styles: 'all',
                    paste_merge_formats: true,
                    nonbreaking_force_tab: false,
                    paste_auto_cleanup_on_paste: false,
                    fontsize_formats: '10px 11px 12px 14px 16px 18px 20px 24px',
                    style_formats: [
                        {
                            title: '首行缩进',
                            block: 'p',
                            styles: { 'text-indent': '2em' }
                        },
                        {
                            title: '行高',
                            items: [
                                {title: '1', styles: { 'line-height': '1' }, inline: 'span'},
                                {title: '1.5', styles: { 'line-height': '1.5' }, inline: 'span'},
                                {title: '2', styles: { 'line-height': '2' }, inline: 'span'},
                                {title: '2.5', styles: { 'line-height': '2.5' }, inline: 'span'},
                                {title: '3', styles: { 'line-height': '3' }, inline: 'span'}
                            ]
                        }
                    ],
                    font_formats: `
                        微软雅黑=微软雅黑;
                        宋体=宋体;
                        黑体=黑体;
                        仿宋=仿宋;
                        楷体=楷体;
                        隶书=隶书;
                        幼圆=幼圆;
                        Andale Mono=andale mono,times;
                        Arial=arial, helvetica,
                        sans-serif;
                        Arial Black=arial black, avant garde;
                        Book Antiqua=book antiqua,palatino;
                        Comic Sans MS=comic sans ms,sans-serif;
                        Courier New=courier new,courier;
                        Georgia=georgia,palatino;
                        Helvetica=helvetica;
                        Impact=impact,chicago;
                        Symbol=symbol;
                        Tahoma=tahoma,arial,helvetica,sans-serif;
                        Terminal=terminal,monaco;
                        Times New Roman=times new roman,times;
                        Trebuchet MS=trebuchet ms,geneva;
                        Verdana=verdana,geneva;
                        Webdings=webdings;
                        Wingdings=wingdings,zapf dingbats
                    `,
                    images_upload_handler: function (blobInfo, success, failure) {
                        if (blobInfo.blob().size > self.maxsize) {
                            failure('文件体积过大')
                        }
                        if (self.accept.indexOf(blobInfo.blob().type) >= 0) {
                            uploadPic()
                        } else {
                            failure('图片格式错误')
                        }
                        function uploadPic () {
                            let param = {
                                'folder':'editor',
                                'file':blobInfo.blob(),
                                'onError':()=>{},
                                'onSuccess':()=>{},
                                'onProgress':()=>{}
                            }
                            oss.uploadFile(param).then(response => {
                                if (response.hasOwnProperty('url')) {
                                    self.$emit('on-upload-complete' , [
                                        response, success, failure
                                    ])
                                }else{
                                    self.$emit('on-upload-fail')
                                    failure('上传失败')
                                    return
                                }
                            })
                        }
                    },
                    // 挂载的DOM对象
                    selector: `#${this.Id}`,
                    setup: (editor) => {
                        editor.on(
                            'init', () => {
                                self.$emit('on-ready')
                                editor.setContent(self.value)
                            }
                        )
                        editor.on(
                            'input change undo redo', () => {
                                self.$emit('catchEditorData', editor.getContent())
                            }
                        )
                    }
                }
            }
        },
        mounted(){
            tinymce.init({})
        },
        beforeDestroy () {
            this.$emit('on-destroy')
            tinymce.remove('#'+this.id)
        }
    }
</script>