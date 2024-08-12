import { useState, useEffect } from 'react';
import { Inertia } from "@inertiajs/inertia";
import { TreeSelect } from 'primereact/treeselect';
import { subscribe } from "@/Components/js/Events.js";
import { NodeCategories } from "@/Services/NodeCategories.jsx"
import { Button } from "primereact/button";
import { Dialog } from "primereact/dialog";

export default function AddProduct() {
    const [nodes, setNodes] = useState({});
    const [selectedNodeKey, setSelectedNodeKey] = useState(null);

    let category = {};

    const [isShow, setShow] = useState(false);
    subscribe("modals", (data) =>{
        if(data.detail === 'hide'){
            setShow(false)
        }
    });
    subscribe("modal-category-add", (data) =>{
        if(data.detail === 'show'){
            setShow(true)
        }else if(data.detail === 'hide'){
            setShow(false)
        }
    });

    const handelSubmit = async (event) => {
        event.preventDefault();
        console.log(category)
        axios.post('/category', category)
            .then(res => {
                Inertia.reload()
            })
    }

    useEffect(() => {
        NodeCategories.getTreeNodes().then((data) => setNodes(data));
    }, []);

    const footerContent = (
        <div>

            <Button label="No" icon="pi pi-times" onClick={() => {
                setShow(false)
            }} className="p-button-text" />
            <Button label="Yes" icon="pi pi-check" onClick={() => {
                //setShow(false)
            }} autoFocus />
        </div>
    );
    return (
        <div className="card flex justify-content-center">
            <Dialog header="Add Category" visible={isShow} style={{width: '50vw'}} onHide={() => {
                if (!isShow) return;
                setShow(false);
            }} footer={footerContent}>
                <div className="m-0">
                    <form onSubmit={handelSubmit}>
                        <label htmlFor="name">Name:</label>
                        <input type="text" id="name" className="input" name="name" onChange={event => {
                            category.name = event.target.value;
                        }}/>
                        <br/>
                        <TreeSelect name="categories" value={selectedNodeKey} onChange={(e) => setSelectedNodeKey(e.value)}
                                    options={nodes}
                                    metaKeySelection={false}
                                    className="md:w-20rem w-full" selectionMode="checkbox" display="chip"
                                    placeholder="Select Items"></TreeSelect>
                        <br />
                        <Button label="Add As Sibling" severity="secondary"/>
                        <Button label="Add As Child" severity="secondary" />
                    </form>
                </div>
            </Dialog>
        </div>
    );
}
