import { useState, useEffect } from 'react'
import './App.css'
import axios from 'axios';
import  { useForm }  from  "react-hook-form";


function App() {
    const [count, setCount] = useState(0)
    const [clients, setClients] = useState([])

    const fetchClients = async () => {
        try {
            const jobsResponse = await axios.get('/api/clients');
            setClients(jobsResponse.data);
        } catch (error) {
            // setErrorJobs(error.message);
            // notifyError(error.message);
            console.log('error', error )
        } finally {
            // setLoadingJobs(false);
        }
    };

    useEffect(() => {
        fetchClients();
    }, []);

    const oneMore = (number)  => {
        return number + 1;
    }

    const displayNewClientForm = () => {
        let newClientForm = document.getElementById('new-client-block');
        newClientForm.style.display = 'block';
    }
    const hideNewClientForm = () => {
        let newClientForm = document.getElementById('new-client-block');
        newClientForm.style.display = 'none';
    }


    function NewClientForm() {

        const { register, handleSubmit, formState: { errors } } = useForm();

        const onSubmit = (data) => {
            // console.log(data);
            newClient(data);
        };

        const newClient = async (values) => {
            console.log('create job clicked with values', values);

            const data = {
                ...values,
            };

            axios.request({
                method: 'PUT',
                url: `/api/clients`,
                data: data,
            }).then((response) => {
                if ( response.status !== 200 ) {
                    throw new Error(response.data); // TODO response.error
                }
                if (response !== undefined) {
                    // notifySuccess(`Job created successfully`);
                    console.log( 'response.data', response.data);
                    setClients([response.data, ...clients]);
                    hideNewClientForm();
                }
            }).catch((error) => {
                console.log('error response:', error.response.data);
                alert(error.response.data);
                // notifyError(error.response.data);
            });

            return false;
        }

        return (
            <>
                <button onClick={displayNewClientForm}>Naujas klientas</button>
                <div id={"new-client-block"} style={{"display": "none"}}>
                    <form onSubmit={handleSubmit(onSubmit)}>
                        <dl>
                            <dt><label htmlFor={"name"}>Vardas</label></dt>
                            <dd><input type={"text"} id={"name"} {...register("name")}/></dd>
                            <dt><label htmlFor={"family_name"}>Pavardė</label></dt>
                            <dd><input type={"text"} id={"family_name"} {...register("family_name")}/></dd>
                            <dt><label htmlFor={"birth_date"}>Gimimo Data (pvz. 1977-06-30)</label></dt>
                            <dd><input type={"text"} id={"birth_date"} {...register("birth_date")}/></dd>
                        </dl>
                        <button type={"submit"} name={"submit"}>Kurti</button>
                    </form>
                </div>
            </>
        )
    }

    return (
        <>
            <h1>Klientai</h1>

            <NewClientForm/>

            <table>
                {clients.map((client, index) =>
                    (
                        <tr>
                            <td>{oneMore(index)}</td>
                            <td>{client.name}</td>
                            <td>{client.family_name}</td>
                            <td>{client.birth_date}</td>
                        </tr>
                    ))}
            </table>
        </>
    )
}

export default App
