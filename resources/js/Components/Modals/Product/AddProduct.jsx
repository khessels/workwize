export default function AddProduct() {
    const handelSubmit = async (event) => {
        event.preventDefault();
        console.log(product)
        axios.post('/product', product)
            .then(res => {
                window.location.reload()
            })
    }

    return (
        <dialog id="mdl_add_product" className="modal">
            <div className="modal-box">
                <form method="dialog">
                    {/* if there is a button in form, it will close the modal */}
                    <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <form onSubmit={handelSubmit}>
                    <h3 className="font-bold text-lg">Add New Product</h3>
                    <label htmlFor="name">Name:</label>
                    <input type="text" className="input" name="name" onChange={event => {
                        product.name = event.target.value;
                    }}/>

                    <br/>
                    <label htmlFor="stock">Stock Quantity:</label>
                    <input name="stock" className="input max-w-xs" type="number" onChange={event => {
                        product.stock = event.target.value;
                    }}/>

                    <br/>
                    <label htmlFor="price">Price:</label>
                    <input name="price" className="input max-w-xs" type="number" onChange={event => {
                        product.price = event.target.value;
                    }}/>

                    <label htmlFor="active">Active:</label>
                    <select name="active" className="select max-w-xs" onChange={event => {
                        product.active = event.target.value;
                    }}>
                        <option disabled>Select Active state</option>
                        <option value='YES'>Yes</option>
                        <option value='NO'>No</option>
                    </select>

                    <div className="modal-action">
                        <button className="btn btn-warning" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </dialog>
    );
}
