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
}();
