import {subscribe} from "@/Components/js/Events.js";
import {useState} from "react";
import {Button} from "primereact/button";
import {Dialog} from "primereact/dialog";

export default function EditProduct(value) {
    const [isShow, setShow] = useState(false);
    subscribe("modals", (data) =>{
        if(data.detail === 'hide'){
            setShow(false)
        }
    });
    subscribe("modal-product-edit", (data) =>{
        if(data.detail === 'show'){
            setShow(true)
        }else if(data.detail === 'hide'){
            setShow(false)
        }
    });
    const footerContent = (
        <div>
            <Button label="No" icon="pi pi-times" onClick={() => {
                setShow(false)
            }} className="p-button-text" autoFocus />
            <Button label="Yes" icon="pi pi-check" onClick={() => {
                axios.put('/product', value.value)
                    .then(res => {
                        window.location.reload()
                    })
            }} />
        </div>
    );
    return (
        <div className="card flex justify-content-center">
            {/*<Button label="Show" icon="pi pi-external-link" onClick={() => setAddProductVisible(true)}/>*/}
            <Dialog header="Edit Product" visible={isShow} style={{width: '50vw'}} onHide={() => {
                if (!isShow) return;
                setShow(false);
            }} footer={footerContent}>
                <input type="hidden" name='id' value={value.value.id}/>
                <label htmlFor="name">Name:</label>
                <input name="name" className="input" defaultValue={value.value.name} onChange={event => {
                    value.value.name = event.target.value;
                }}/>
                <br/>
                <label htmlFor="quantity">Stock Quantity:</label>
                <input name="quantity" id="quantity" className="input max-w-xs" type="number"
                       defaultValue={value.value.stock}
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
            </Dialog>
        </div>
);}
