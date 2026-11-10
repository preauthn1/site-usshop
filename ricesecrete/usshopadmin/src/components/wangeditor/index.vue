<template>
    <div class="WangEditorInputBox">
        <Toolbar style="border-bottom: 1px solid #DCDFE6;" :editor="editor" :defaultConfig="toolbarConfig"
            :mode="mode" />
        <Editor style="height: 360px; overflow-y: hidden;" v-model="html" :defaultConfig="editorConfig" :mode="mode"
            @onCreated="onCreated" />
    </div>
</template>

<script>
import { Editor, Toolbar } from '@wangeditor/editor-for-vue';
import '@wangeditor/editor/dist/css/style.css';
import common from "@/api/modules/common"

export default {
    name: 'WangEditorInput',
    props: {
        content: {
            type: String,
            default: ""
        }
    },
    components: { Editor, Toolbar },
    data() {
        return {
            editor: null,
            html: '',
            toolbarConfig: {
                toolbarKeys: [
                    'headerSelect',
                    // 'header1',
                    // 'header2',
                    // 'header3',
                    'blockquote',
                    'bold',
                    'underline',
                    'italic',
                    {
                        key: 'group-more-style',
                        title: '···',
                        menuKeys: ['through', 'code', 'sup', 'sub', 'clearStyle'],
                    },
                    'color',
                    'bgColor',
                    'fontSize',
                    'fontFamily',
                    'lineHeight',
                    'bulletedList',
                    'numberedList',
                    'todo',
                    {
                        key: 'group-justify',
                        title: '对齐',
                        menuKeys: ['justifyLeft', 'justifyRight', 'justifyCenter', 'justifyJustify'],
                    },
                    {
                        key: 'group-indent',
                        title: '缩进',
                        menuKeys: ['indent', 'delIndent'],
                    },
                    'emotion',
                    'insertLink',
                    // 'editLink',
                    // 'unLink',
                    // 'viewLink',
                    {
                        key: 'group-image',
                        title: '图片',
                        menuKeys: ['insertImage', 'uploadImage'],
                    },
                    // 'deleteImage',
                    // 'editImage',
                    // 'viewImageLink',
                    // 'deleteVideo',
                    'insertTable',
                    'codeBlock',
                    // 'codeSelectLang',
                    'divider',
                    // 'deleteTable',
                    'undo',
                    'redo'
                ]
            },
            editorConfig: {
                placeholder: '请输入内容...', MENU_CONF: {
                    uploadImage: {
                        customUpload: async (files, insert) => {
                            //上傳圖片
                            let param_file = new FormData();
                            param_file.append("file", files);
                            param_file.append("folder", 'edit_detail');
                            console.log(param_file);
                            await common.upload(param_file).then((response) => {
                                if (response.code == 1) {
                                    insert(response.data[0].url); //成功 插入图片
                                }
                            });
                        }
                    }
                }
            },
            mode: 'default', // or 'simple'
        }
    },
    methods: {
        onCreated(editor) {
            this.editor = Object.seal(editor) // 一定要用 Object.seal() ，否则会报错
        },
    },
    mounted() {

    },
    beforeDestroy() {
        const editor = this.editor
        if (editor == null) return
        editor.destroy() // 组件销毁时，及时销毁编辑器
    },
    watch: {
        content: {
            handler(val) {
                this.html = val
            },
        },
        html: {
            handler(val) {
                this.$emit("htmlChange", val);
            }
        }
    },
};
</script>

<style scoped lang=scss>
.WangEditorInputBox {
    border: 1px solid #DCDFE6;
    border-radius: 4px;
    overflow: hidden;
    --w-e-toolbar-bg-color: #f5f5f5;
}

::v-deep(.w-e-modal button) {
    height: 40px;
    padding: 0 10px;
}

::v-deep(.babel-container) {
    margin-bottom: 5px;

    input {
        height: 26px;
    }

    span {
        margin-bottom: 0;
        line-height: 20px;
        height: 20px;
    }
}

::v-deep(.w-e-text-placeholder) {
    top: 6px;
}

::v-deep(.w-e-panel-content-emotion) {
    height: 200px;
    overflow: auto;
}
</style>
