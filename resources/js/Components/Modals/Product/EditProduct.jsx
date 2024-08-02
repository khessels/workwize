export default function AddProduct(value) {
    return (
        <dialog id="mdl_edit_product" className="modal">
            <div className="modal-box">
                <h3 className="font-bold text-lg">Edit Product: {value.value.name}</h3>
                <form method="dialog">
                    {/* if there is a button in form, it will close the modal */}
                    <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <input type="hidden" name='id' value={value.value.id}/>
                <label>Name:
                    <input className="input" defaultValue={value.value.name} onChange={event => {
                        value.value.name = event.target.value;
                    }}/>
                </label>
                <br/>
                <label>Stock Quantity:
                    <input className="input max-w-xs" type="number" defaultValue={value.value.stock} onChange={event => {
                        value.value.stock = event.target.value;
                    }}/>
                </label>
                <br/>
                <label>Price:
                    <input className="input max-w-xs" type="number" defaultValue={value.value.price} onChange={event => {
                        value.value.price = event.target.value;
                    }}/>
                </label>
                <label>Active:
                    <select className="select max-w-xs" defaultValue={value.value.active} onChange={event => {
                        value.value.active = event.target.value;
                    }}>
                        <option value='YES'>Yes</option>
                        <option value='NO'>No</option>
                    </select>
                </label>
                <div className="modal-action">
                    <button className="btn btn-warning" type="submit" onClick={() => {
                        axios.put('/product', value.value)
                            .then(res => {
                                window.location.reload()
                            })
                    }}>Save
                    </button>

                </div>
            </div>
        </dialog>
    );
}
