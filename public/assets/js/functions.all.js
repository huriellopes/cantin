const FunctionsAll = function () {
    getApi = (endpoint, type, data = null) => {
        return axios({
            method: type,
            url: endpoint,
            data,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').attributes[1].value
            }
        })
    }

    notify = (typeNotify, title, message) => {
        return new Notify ({
            status: typeNotify,
            title: title,
            text: message,
            effect: 'slide',
            speed: 300,
            customClass: '',
            customIcon: '',
            showIcon: true,
            showCloseButton: true,
            autoclose: true,
            autotimeout: 3000,
            gap: 20,
            distance: 20,
            type: 1,
            position: 'right top'
        })
    }

    dataTables = (table) => {
        return new DataTable(`${table}`, {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
            },
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            "pageLength": 10,
            "order": [[ 0, "desc" ]],
        });
    }
}();
