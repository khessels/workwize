import { useState, useEffect } from 'react';
import { TreeSelect } from 'primereact/treeselect';
import { ServiceCategories } from "@/Services/Categories"
import { Button } from 'primereact/button';
import { Dialog } from 'primereact/dialog';
import { subscribe } from "@/Components/js/Events.js";
import { InputText } from 'primereact/inputtext';
import { InputNumber } from 'primereact/inputnumber';

import { Dropdown } from 'primereact/dropdown';
import { MultiSelect } from 'primereact/multiselect';
import {Inertia} from "@inertiajs/inertia";

export default function AddProduct( props) {

    const [selectedNodeKeys, setSelectedNodeKeys] = useState(null);
    const [product, setProduct] = useState({name:'', price:0, stock:0, active:'NO', tags:[], categories:[]})
    const [active, setActive] = useState({})
    const [tags, setTags] = useState([])
    const [tag, setTag] = useState([])

    const [topics, setTopics] = useState()
    const [topic, setTopic] = useState()
    const [isShow, setShow] = useState(false);
    const yesNoOptions = [
        {name:'YES', code:'YES' },
        {name:'NO', code:'NO' }
    ];
    const [categories, setCategories] = useState([]);
    useEffect(() => {
        axios.get('/category/tree/root',  {headers: {"x-response-format":'primereact'}} )
            .then(response => {
                setCategories(response.data[0].children);
            })
    }, []);

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

    useEffect(() => {
        axios.get('/tag/topics', { headers: { 'format': 'select' }})
            .then(res => {
                setTopics( res.data);
            })

    }, []);

    const footerContent = (
        <div>
            <Button label="No" icon="pi pi-times" onClick={() => {
                setShow(false)
            }} className="p-button-text"  />
            <Button label="Yes" icon="pi pi-check" onClick={() => {
                axios.post('/product', product)
                    .then(res => {
                        Inertia.reload()
                    })
                setShow(false)
            }} />
        </div>
    );

    return (
        <div className="card flex justify-content-center">
            {/*<Button label="Show" icon="pi pi-external-link" onClick={() => setAddProductVisible(true)}/>*/}
            <Dialog header="Add Product" visible={isShow} style={{width: '50vw'}} onHide={() => {
                if (!isShow) return;
                setShow(false);
            }} footer={footerContent}>
                <label htmlFor={"isActive"}>Active:</label><br/>
                <Dropdown id={"isActive"} name={"active"} value={ active} options={yesNoOptions} optionLabel="name" placeholder="Actively being sold"
                          className="w-full md:w-14rem" onChange={(e) => {
                    product.active = e.value.code
                    setActive(e.value)
                    setProduct(product)
                }} />
                <br/>
                <label htmlFor={"treeSelect"}>Select Categories</label><br/>
                <TreeSelect id={"treeSelect"} name={"categories"} value={selectedNodeKeys}
                            onChange={(e) => {
                                product.categories = e.value
                                setProduct(product)
                                setSelectedNodeKeys(e.value)
                            }}
                            options={categories}
                            metaKeySelection={false} className="md:w-20rem w-full" selectionMode="checkbox"
                            placeholder="Select Items"></TreeSelect>
                <br/>
                <label htmlFor={"name"}>Name:</label><br/>
                <InputText id={"name"} name={"name"} onChange={(e) => {
                    product.name = e.target.value
                    setProduct( product)
                }}/>
                <br/>
                <label htmlFor={"price"}>Price:</label><br/>
                <InputNumber id={"price"} name={"price"} onChange={(e) => {
                    product.price = e.value
                    setProduct( product)
                }}/>
                <br/>
                <label htmlFor={"stock"}>Stock:</label><br/>
                <InputNumber id={"stock"} name={"stock"} onChange={(e) => {
                    product.stock = e.value
                    setProduct( product)
                }}/>
                <br/>

                <label htmlFor={"topics"}>Topics:</label><br/>
                <Dropdown id={"topics"} value={topic} options={topics} optionLabel="name"
                          placeholder="Select a topic" className="w-full md:w-14rem" onChange={(e) => {
                    setTopic( e.value)
                    axios.get('/tags/' + e.value.code, {headers: {'format': 'select'}})
                        .then(res => {
                            setTags(res.data);
                        })
                }}/>

                <MultiSelect id={"tags"} value={tag} options={tags} optionLabel="name" placeholder="Select tags"
                             className="w-full md:w-14rem" onChange={(e) => {
                                let tags = [];
                                for(let x = 0; x < e.value.length; x++) {
                                    tags.push( topic.code + '.' + e.value[x].code)
                                }
                                product.tags = tags
                                setProduct(product)
                                setTag(e.value)
                }}/>
                <br/>

            </Dialog>
        </div>
    );
}
