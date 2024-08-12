import { useState, useEffect } from 'react';
import { TreeSelect } from 'primereact/treeselect';
import { NodeCategories } from "@/Services/NodeCategories"
import { Button } from 'primereact/button';
import { Dialog } from 'primereact/dialog';
import { subscribe } from "@/Components/js/Events.js";

export default function AddProduct( categories) {
    const [nodes, setNodes] = useState({});

    const [isShow, setShow] = useState(false);
    subscribe("modals", (data) =>{
        if(data.detail === 'hide'){
            setShow(false)
        }
    });
    subscribe("modal-product-add", (data) =>{
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
                //setShow(false)
            }} />
        </div>
    );

    const handelSubmit = async (event) => {
        event.preventDefault();
        console.log(product)
        axios.post('/product', product)
            .then(res => {
                window.location.reload()
            })
    }
    let product = {};

    // useEffect(() => {
    //     NodeService.getTreeNodes().then((data) => setNodes(data));
    // }, []);

    return (
        <div className="card flex justify-content-center">
            {/*<Button label="Show" icon="pi pi-external-link" onClick={() => setAddProductVisible(true)}/>*/}
            <Dialog header="Add Product" visible={isShow} style={{width: '50vw'}} onHide={() => {
                if (!isShow) return;
                setShow(false);
            }} footer={footerContent}>
                <p className="m-0">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                    nulla pariatur.
                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id
                    est laborum.
                </p>
            </Dialog>
        </div>
    );
}
