const ListSuggestions = function () {
    let list = () => {
        getApi('/api/suggestions/list', 'GET', null).then(res => {
            if (res.status === 200 && res.data.data.length > 0) {
                let options = '<option selected disabled>Selecione a sugestão</option>'

                res.data.data.map(suggestion => {
                    options += `<option value="${suggestion.id}">${suggestion.type_suggestion}</option>`
                })

                document.querySelector('#sugestion_id').innerHTML = options
            }
        })
    }

    let changedSelect = () => {
        document.querySelector('#sugestion_id').addEventListener('change', function () {
            document.querySelector('#suggestionText').style = 'display: block'
        })
    }

    return {
        init: function () {
            list()
            changedSelect()
        }
    }
}()

window.addEventListener('load', function () {
    ListSuggestions.init()
})
