const GetCep = function () {

    let maskCep = () => {
        document.querySelector('#cep').addEventListener('blur', function (e) {
            if (!e.target.value) return ""

            e.target.value = e.target.value.replace(/\D/g,'')
            e.target.value = e.target.value.replace(/(\d{5})(\d)/,'$1-$2')
            return e.target.value
        })
    }

    let cep = () => {
        let cep = document.getElementById('cep')

        cep.addEventListener('blur',  function (e) {
            if(e.target.value === cep.value){
                getApi('/api/cep/get', 'POST', {zipcode: cep.value}).then(response => {
                    if (response.status === 200) {
                        document.querySelector('#address').value = response.data.data.address
                        document.querySelector('#neighborhood').value = response.data.data.neighborhood
                        document.querySelector('#complement').value = response.data.data.complement

                        const options = document.querySelector('#state').options

                        for (let option of options) {
                            const state = option.getAttribute('data-acronyon')

                            if (state !== null && state === response.data.data.uf) {
                                option.setAttribute('selected', 'true')
                                return selectState(option.value)
                            }

                            setTimeout(function () {
                                const optionsLocality = document.querySelector('#city').options

                                for (let option of optionsLocality) {
                                    const locality = option.getAttribute('data-value')

                                    if (locality !== null && locality === response.data.data.locality) {
                                        option.setAttribute('selected', 'true')
                                        return
                                    }
                                }
                            }, 1000)
                        }
                    }

                }).catch(error => {
                    if (error.response.status === 400) {
                        document.querySelector('#cep').value = ''
                        document.querySelector('#address').value = ''
                        document.querySelector('#neighborhood').value = ''
                        document.querySelector('#complement').value = ''
                        document.querySelector('#number').value = ''
                        document.querySelector('#state').value = ''
                        document.querySelector('#city').value = ''
                        alert('Cep não encontrado!')
                    }
                    console.log(error)
                })
            }
        })
    }

    let selectState = async (state) => {
        const cities = await getApi('/api/cities/list', 'POST', {state_id: state})

        if (cities.status === 200 && cities.data.data.length > 0) {
            let options = '<option selected disabled>Selecione a cidade</option>'
            cities.data.data.map(city => {
                options += `<option value="${city.id}" data-value="${city.city}">${city.city}</option>`
            })
            document.querySelector('#city').innerHTML = options
        }
    }

    return {
        init: function () {
            maskCep()
            cep()
        }
    }
}()

window.addEventListener('load', function () {
    GetCep.init()
})
