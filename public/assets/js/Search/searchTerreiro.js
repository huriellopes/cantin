const SearchTerreiro  = function () {
    let search = () => {
        getTerreiro(null)

        document.querySelector('#uf').addEventListener('change', (e) => {
            let uf = e.target.value

            getTerreiro(uf)
        })
    }

    let getTerreiro = (uf = null) => {
        getApi('/api/terreiros/search', 'POST', {state_id: uf}).then(res => {
            if (res.data.status === 200 && res.data.data.length > 0) {
                res.data.data.each((i, v) => {
                    console.log(i, v)
                })
                res.data.data.each(item => {
                    document
                        .querySelector('#table-terreiro tbody')
                        .innerHTML = `<tr>
                                            <td>${item.id}</td>
                                            <td>${item.name}</td>
                                            <td>${item.nation}</td>
                                            <td>${item.phone}</td>
                                            <td>${item.orunko}</td>
                                            <td>${item.fundation}</td>
                                            <td>${item.leadership}</td>
                                            <td>${item.state}</td>
                                            <td>${item.city}</td>
                                          </tr>`
                })
            } else {
                document
                    .querySelector('#table-terreiro tbody')
                    .innerHTML = `<tr>
                                     <td colspan="9" class="text-center font-weight-bold">Nenhum Terreiro encontrado</td>
                                  </tr>`
            }
        }).catch(err => {
            console.log(err)
            if (err.response.status === 404) {
                document
                    .querySelector('#table-terreiro tbody')
                    .innerHTML = `<tr>
                                        <td colspan="9" class="text-center font-weight-bold">Nenhum Terreiro encontrado</td>
                                      </tr>`
            }
        })
    }

    return {
        init: function () {
            search()
        }
    }
}()

window.addEventListener('load', () => {
    SearchTerreiro.init()
})
