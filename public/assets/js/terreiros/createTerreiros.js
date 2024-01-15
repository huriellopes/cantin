const CreateTerreiros = function () {
    let create = () => {
        const formTerreiro = document.querySelector('#form-terreiros')

        formTerreiro.addEventListener('submit', (e) => {
            e.preventDefault()
            const data = new FormData(formTerreiro)

            getApi(formTerreiro.getAttribute('action'), 'POST', data).then(res => {
                if (res.status === 201) {
                    notify('success', 'Sucesso', 'Terreiro cadastrado com sucesso! Responda as perguntas em seguida.')
                    setTimeout(function () {
                        window.location.href = `/terreiros/${res.data.data.id}/questoes`
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
    CreateTerreiros.init()
})
