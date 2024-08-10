import {subscribe} from "@/Components/js/Events.js";

export default function EditProduct(value) {
    subscribe("modal-all", (data) =>{
        if(data.action === 'show'){

        }else if(data.action === 'hide'){

        }
    });

    subscribe("modal-product-edit", (data) =>{
        if(data.action === 'show'){

        }else if(data.action === 'hide'){

        }
    });

    return (
        <dialog id="mdl_edit_product" className="modal">
            <div className="modal-box">
                <h3 className="font-bold text-lg">Edit Product: {value.value.name}</h3>
                <form method="dialog">
                    {/* if there is a button in form, it will close the modal */}
                    <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <input type="hidden" name='id' value={value.value.id}/>
                <label htmlFor="name">Name:</label>
                <input name="name" className="input" defaultValue={value.value.name} onChange={event => {
                    value.value.name = event.target.value;
                }}/>
                <br/>
                <label htmlFor="quantity">Stock Quantity:</label>
                <input name="quantity" id="quantity" className="input max-w-xs" type="number" defaultValue={value.value.stock}
                       onChange={event => {
                           value.value.stock = event.target.value;
                       }}/>
                <br/>
                <label htmlFor="price">Price:</label>
                <input name="price" className="input max-w-xs" type="number" defaultValue={value.value.price}
                       onChange={event => {
                           value.value.price = event.target.value;
                       }}/>

                <label htmlFor="active">Active:</label>
                <select name="active" className="select max-w-xs" onChange={event => {
                    value.value.active = event.target.value;
                }}>
                    <option value="YES" defaultValue={value.value.active === "YES"}>
                        Yes
                    </option>
                    <option value="NO" defaultValue={value.value.active === "NO"}>
                        No
                    </option>
                </select>

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
