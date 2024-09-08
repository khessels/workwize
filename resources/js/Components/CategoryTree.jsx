import React, { useRef, useState, useEffect } from 'react';
import { Button } from "primereact/button";
import { Tree } from 'primereact/tree';
import { Inertia } from '@inertiajs/inertia'
import { Toolbar} from "primereact/toolbar";
import ModalText from "@/Components/Modals/Text"

export default function CategoryTree({ updateCategoryKey }) {
    const [ categoryName, setCategoryName] = useState();
    const [ selectedCategory, setSelectedCategory] = useState( updateCategoryKey);
    const [ visible, setVisible] = useState(false);

    const [categories, setCategories] = useState([]);
    useEffect(() => {
        axios.get('/category/tree/root',  {headers: {"x-response-format":'primereact'}} )
            .then(response => {
                setCategories(response.data[0].children);
            })
    }, []);

    useEffect(() => {
        if( categoryName !== undefined){
            console.log(categoryName, '- Has changed')
            axios.post('/category/' + selectedCategory + '/sibling/' + categoryName)
                .then(response => {
                    Inertia.reload()
                })
        }
    },[categoryName])

    const startCategoryContent = (
        <React.Fragment>
            <Button className="mr-2" onClick={(event) => {
                setVisible(true)
            }}>Add Sibling</Button>
            <p>Categories</p>
        </React.Fragment>
    );

    return (
        <>
            <div className="card">
                <Toolbar start={startCategoryContent}/>
            </div>

            <Tree selectionMode="single" value={categories} className="w-full md:w-30rem"
                  onSelectionChange={(e) => {
                      updateCategoryKey(e.value)
                      setSelectedCategory(e.value)
                  }}/>
            <ModalText
                Text = { categoryName}
                setText = { setCategoryName}
                visible = { visible}
                setVisible = { setVisible}
            />
        </>
    );
}
