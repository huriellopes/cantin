const ListState = function () {
    let listStates = () => {
        getApi('/api/states/list', 'GET', null).then((res) => {
            if (res.status === 200 && res.data.data.length > 0) {
                let options = '<option selected disabled>Selecione o estado</option>'

                res.data.data.map(state => {
                    options += `<option value="${state.id}" data-acronyon="${state.acronym}">${state.description}</option>`
                })

                document.querySelector('#state').innerHTML = options
            }
        })
    }

    let selectState = () => {
        let state = document.getElementById('state')

        state.addEventListener('change', async function (e) {
            if(e.target.value === state.value){
                const cities = await getApi('/api/cities/list', 'POST', {state_id: state.value})

                if (cities.status === 200 && cities.data.data.length > 0) {
                    let options = '<option selected disabled>Selecione a cidade</option>'
                    cities.data.data.map(city => {
                        options += `<option value="${city.id}">${city.city}</option>`
                    })
                    document.querySelector('#city').innerHTML = options
                }
            }
        })
    }

    return {
        init: function () {
            listStates()
            selectState()
        }
    }
}()

window.addEventListener('load', function () {
    ListState.init()
})
