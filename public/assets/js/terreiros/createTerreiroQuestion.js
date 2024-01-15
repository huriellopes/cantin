const CreateTerreiroQuestion = function () {
    let create = () => {
        const formTerreiroQuestion = document.querySelector('#form-terreiros-question')

        formTerreiroQuestion.addEventListener('submit', (e) => {
            e.preventDefault()
            const data = new FormData(formTerreiroQuestion)

            getApi(formTerreiroQuestion.getAttribute('action'), 'POST', data).then(res => {
                if (res.status === 201) {
                    notify('success', 'Sucesso', 'Questão cadastrada com sucesso!')
                    setTimeout(function () {
                        window.location.href = `/`
                    }, 1000)
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

window.addEventListener('load', () => {
    CreateTerreiroQuestion.init()
})
