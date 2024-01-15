const ListParterns = function () {
    let populate = () => {
        getApi('/api/partners/list', 'GET', null).then(response => {
            if (response.status === 200 && response.data.data.length > 0) {
                response.data.data.map(item => {
                    document.getElementById('partners').innerHTML +=
                        `<div class="col">
                            <figure class="figure">
                                <img src="${item.path_image ?? 'https://xsgames.co/randomusers/assets/avatars/male/46.jpg'}" class="figure-img img-fluid rounded-circle" alt="${item.name}">
                                <figcaption class="figure-caption text-center">${item.name}</figcaption>
                            </figure>
                        </div>`
                })
            }
        }).catch(error => {
            if (error.response.status === 404) {
                document.getElementById('partners').innerHTML = `
                                    <div class="col">
                                        <p class="text-center">Não há parceiros registrados</p>
                                    </div>`
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
    ListParterns.init()
})
