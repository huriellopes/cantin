const CreateTransPeople = function () {
    let create = () => {
        const transPeopleForm = document.querySelector('#formTransPeople')

        transPeopleForm.addEventListener('submit', function (e) {
            e.preventDefault()

            const formData = new FormData(transPeopleForm)

            getApi(transPeopleForm.getAttribute('action'), 'POST', formData)
                .then(res => {
                    if (res.status === 201) {
                        notify('success', 'Sucesso', 'Pessoa trans cadastrada com sucesso!')

                        setTimeout(() => {
                            window.location.href = '/'
                        }, 2000)
                    }
                }).catch(err => {
                    if (err.response.status === 400) {
                        notify('error', 'Erro', 'Error Ao cadastrar!')
                    }
                })
        })
    }

    return {
        init: function () {
            create()
        }
    }
}()

window.addEventListener('load', function () {
    CreateTransPeople.init()
})
