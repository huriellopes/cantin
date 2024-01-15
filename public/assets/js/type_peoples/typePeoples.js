const TypePeoples = function () {
    let listTypePeoples = () => {
        getApi('/api/type-peoples/list', 'GET', null).then(res => {
            if (res.status === 200 && res.data.data.length > 0) {
                let options = '<option selected disabled>Selecione a identidade de gênero</option>'

                res.data.data.map(people => {
                    options += `<option value="${people.id}">${people.type}</option>`
                })

                document.querySelector('#type_people_id').innerHTML = options
            }
        })
    }

    return {
        init: function () {
            listTypePeoples()
        }
    }
}()

window.addEventListener('load', function () {
  TypePeoples.init()
})
