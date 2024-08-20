export const ServiceProducts = {
    async getTreeNodesData(categoryKey)
    {
        console.log("requesting: " + categoryKey)
        await axios.get('/products/category/key/' + categoryKey)
            .then(response => {
                return response.data;
            })
    },

    getTreeNodes(categoryKey)
    {
        return Promise.resolve(this.getTreeNodesData(categoryKey));
    }
};

