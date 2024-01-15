const EditorTextGlobal = function () {
    let initEditor = function () {
        const editor = new EditorJS({
            holder: 'editorjs',
            // tools: {
            //     header: {
            //         class: Header,
            //         inlineToolbar: true
            //     }
            // }
        })
    }

    return {
        init: function () {
            initEditor()
        }
    }
}()

window.addEventListener('load', function () {
    EditorTextGlobal.init()
})
