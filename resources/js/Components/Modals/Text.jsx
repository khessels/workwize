import { useState } from 'react';
import { publish, subscribe} from "@/Components/js/Events.js";
import { Button } from "primereact/button";
import { Dialog } from "primereact/dialog";
import { InputText } from 'primereact/inputtext';
import { FloatLabel} from "primereact/floatlabel";

export default function Text( props) {
    const [isShow, setShow] = useState(false);
    let text = undefined;
    return (
        <div className="card flex justify-content-center">
            <Dialog visible={props.visible} style={{width: '30vw'}}
            content={({ hide }) => (
                <div className="flex flex-column px-8 py-5 gap-4" style={{
                    borderRadius: '12px',
                    backgroundImage: 'radial-gradient(circle at left top, var(--yellow-50), var(--yellow-100))' }}>
                    <InputText
                        id='numberText'
                        onChange={(e) => {
                            text = e.target.value;
                        }}/>
                    <Button onClick={(e) => {
                        props.setVisible(false)
                        props.setText(text)
                    }}>
                        Update
                    </Button>
                </div>
            )} />
        </div>
    );
}
