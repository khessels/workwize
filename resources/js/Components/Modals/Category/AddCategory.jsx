import {Inertia} from "@inertiajs/inertia";
import { TreeSelect } from 'primereact/treeselect';

export default function AddProduct() {
    let category = undefined;
    let selectedNodeKeys;
    let nodes
    const handelSubmit = async (event) => {
        event.preventDefault();
        console.log(category)
        axios.post('/category', category)
            .then(res => {
                Inertia.reload()
            })
    }

    return (
        <dialog id="mdl_add_category" className="modal">
            <div className="modal-box">
                <form method="dialog">
                    {/* if there is a button in form, it will close the modal */}
                    <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <form onSubmit={handelSubmit}>
                    <h3 className="font-bold text-lg">Add New Category</h3>
                    <label htmlFor="name">Name:</label>
                    <input type="text" id="name" className="input" name="name" onChange={event => {
                        category.name = event.target.value;
                    }}/>
                    <br />
                    <label htmlFor="categories">Select:
                    </label>
                    <TreeSelect name="categories" value={selectedNodeKeys} onChange={(e) => setSelectedNodeKeys(e.value)} options={nodes}
                                metaKeySelection={false}
                                className="md:w-20rem w-full" selectionMode="checkbox" display="chip"
                                placeholder="Select Items"></TreeSelect>
                    <button className="btn">Add As Sibling</button>
                    <button className="btn">Add As Child</button>

                    <div className="modal-action">
                        <button className="btn btn-warning" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </dialog>
    );
}
