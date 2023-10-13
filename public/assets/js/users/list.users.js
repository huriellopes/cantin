const ListUsers = function () {
    let loadingDataTable = function (tbody) {
        tbody.innerHTML =
            "<tr class='text-center'>" +
                "<td class='text-white' colspan='6'>Carregando os dados....</td>" +
            "</tr>"
    }

    let list = function () {
        let tableUsers = document.getElementById('table-users')
        let tbody = tableUsers.querySelector('tbody')

        getApi('/api/users/list', 'GET', null).then(response => {
            if (response.status === 200 && response.data.data.length > 0) {
                loadingDataTable(tbody)

                setTimeout(function () {
                    tbody.innerHTML = ""
                    response.data.data.map(item => {
                        let status = ""

                        if (item.status === "Ativo") {
                            status = `
                                <a href='/api/users/${item.id}/delete' class='btn btn-primary' id='btnDel'>
                                    Excluir
                                </a>
                            `
                        } else {
                            status = `
                                <a href='/api/users/${item.id}/restore' class='btn btn-primary' id='btnRestore'>
                                    Recuperar
                                </a>
                            `
                        }

                        tbody.innerHTML =
                            "<tr>" +
                                `<td style='color: #FFFFFF;'>${item.name}</td>` +
                                `<td style='color: #FFFFFF;'>${item.email}</td>` +
                                `<td style='color: #FFFFFF;'>${item.level}</td>` +
                                `<td style="color: #FFFFFF">${item.status}</td>` +
                                `<td style='color: #FFFFFF;'>${item.created_at}</td>` +
                                `<td>
                                    ${status}
                                </td>` +
                            "</tr>"
                    })
                }, 3000)
            } else {
                tbody.innerHTML =
                    "<tr class='text-center'>" +
                        "<td colspan='6' class='text-white'>Não há dados...</td>" +
                    "</tr>"
            }
        })
    }

    let del = () => {
        setTimeout(function () {
            const btnDel = document.getElementById('btnDel')

            const btnRestore = document.getElementById('btnRestore')

            if (btnDel !== null) {
                btnDel.addEventListener('click', function (e) {
                    e.preventDefault()

                    let url = this.attributes[0].value

                    getApi(url, 'POST', null).then(response => {
                        if (response.status === 200) {
                            setTimeout(function () {
                                location.reload(true)
                            }, 1000)
                        }
                    })
                })
            }

            if (btnRestore !== null) {
                btnRestore.addEventListener('click', function (e) {
                    e.preventDefault()

                    let url = this.attributes[0].value

                    getApi(url, 'POST', null).then(response => {
                        if (response.status === 200) {
                            setTimeout(function () {
                                location.reload(true)
                            }, 1000)
                        }
                    })
                })
            }
        }, 4000)
    }

    return {
        init: function () {
            list()
            del()
        }
    };
}()

window.addEventListener('load', function () {
    ListUsers.init()
})
