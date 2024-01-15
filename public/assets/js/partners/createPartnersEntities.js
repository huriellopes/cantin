const CreatePartnersEntities = function () {
    let create = () => {
        const formPartnerEntity = document.querySelector('#formPartnerEntity')

        formPartnerEntity.addEventListener('submit', function (event) {
            event.preventDefault()

            const editor = document.querySelector('.se-wrapper-inner.se-wrapper-wysiwyg.sun-editor-editable').innerHTML

            const data = new FormData()
            data.set('name', document.querySelector('#name').value)
            data.set('email', document.querySelector('#email').value)
            data.set('phone', document.querySelector('#phone').value)
            data.set('zipcode', document.querySelector('#cep').value)
            data.set('address', document.querySelector('#address').value)
            data.set('number', document.querySelector('#number').value)
            data.set('complement', document.querySelector('#complement').value)
            data.set('neighborhood', document.querySelector('#neighborhood').value)
            data.set('state_id', document.querySelector('#state').value)
            data.set('city_id', document.querySelector('#city').value)
            data.set('activity_carried_out', editor)

            getApi(formPartnerEntity.getAttribute('action'), 'POST', data).then(res => {
                console.log(res)
                if (res.status === 201) {
                    notify('success', 'Sucesso', 'Entidade cadastrada com sucesso!')
                    setTimeout(function () {
                        window.location.href = `/`
                    }, 2500)
                }
            }).catch(error => {
                if (error.response.status === 400) {
                    notify('error', 'Erro', 'Erro ao cadastrar entidade! Entre em contato com o adminstrador do sistema.')
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
    CreatePartnersEntities.init()
})
