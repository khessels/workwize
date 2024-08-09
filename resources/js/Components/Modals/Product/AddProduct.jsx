import { useState, useEffect } from 'react';
import { TreeSelect } from 'primereact/treeselect';
import { NodeService } from "@/Components/NodeService"
import { Button } from 'primereact/button';
import { Dialog } from 'primereact/dialog';


export default function AddProduct( categories, show, setShow) {

    const footerContent = (
        <div>
            <Button label="No" icon="pi pi-times" onClick={() => setVisible(false)} className="p-button-text" />
            <Button label="Yes" icon="pi pi-check" onClick={() => setVisible(false)} autoFocus />
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
    const [nodes, setNodes] = useState(null);
    const [selectedNodeKey, setSelectedNodeKey] = useState(null);

    useEffect(() => {
        NodeService.getTreeNodes().then((data) => setNodes(data));
    }, []);

    const [selectedNode, setSelectedNode] = useState(undefined);

    const setSelectedCategory  = async (event)=> {
        debugger;
    }



    return (
        <div className="card flex justify-content-center">
            {/*<Button label="Show" icon="pi pi-external-link" onClick={() => setAddProductVisible(true)}/>*/}
            <Dialog header="Header" visible={show} style={{width: '50vw'}} onHide={() => {
                if (!visible) return;
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
