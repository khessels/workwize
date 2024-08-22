import React, { useRef, useState, useEffect } from 'react';
import { Button } from "primereact/button";
import { Tree } from 'primereact/tree';
import { Inertia } from '@inertiajs/inertia'
import { Toolbar} from "primereact/toolbar";
import ModalText from "@/Components/Modals/Text"

export default function CategoryTree({ categories, updateCategoryKey }) {

    const [ categoryName, setCategoryName] = useState();
    const [ selectedCategory, setSelectedCategory] = useState( updateCategoryKey);
    const [ visible, setVisible] = useState(false);

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

            <Tree selectionMode="single" value={categories[0].children} className="w-full md:w-30rem"
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
