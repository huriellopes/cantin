const Nations = function () {
    let populate = () => {
        getApi('/api/nacoes/list', 'GET', null).then((res) => {
            if (res.status === 200 && res.data.data.length > 0) {
                let options = '<option selected disabled>Selecione a nação</option>'

                res.data.data.map(nation => {
                    options += `<option value="${nation.id}">${nation.nation}</option>`
                })

                document.querySelector('#nation_terreiro_id').innerHTML = options
            }
        })
    }

    return {
        init: function () {
            populate()
        }
    }
}()

window.addEventListener('load', function () {
    Nations.init()
})

