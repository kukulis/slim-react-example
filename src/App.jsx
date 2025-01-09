import { useState, useEffect } from 'react'
import './App.css'
import axios from 'axios';

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

    const newClient = async (values) => {
        console.log('create job clicked with values', values);

        const data = {
            ...values,
        };

        axios.request({
            method: 'PUT',
            url: `/api/clients`,
            data: data,
        }).catch((error) => {
            console.log('error response:', error.response.data);
            alert(error.response.data);
            // notifyError(error.response.data);
        }).then((response) => {
            if (response !== undefined) {
                // notifySuccess(`Job created successfully`);
                console.log( 'response.data', response.data);
                setClients([response.data, ...clients]);
                hideNewClientForm();
            }
        });

        return false;
    }

  return (
      <>
          <h1>Klientai</h1>

          <button onClick={displayNewClientForm}>Naujas klientas</button>
          <div id={"new-client-block"} style={{"display":"none"}} >
              <form action={newClient} onSubmit={newClient}>
                  <dl>
                      <dt><label htmlFor={"name"}>Vardas</label></dt>
                      <dd><input type={"text"} id={"name"} name={"name"}/></dd>
                      <dt><label htmlFor={"family_name"}>Pavardė</label></dt>
                      <dd><input type={"text"} id={"family_name"} name={"family_name"}/></dd>
                      <dt><label htmlFor={"birth_date"}>Gimimo Data (pvz. 1977-06-30)</label></dt>
                      <dd><input type={"text"} id={"birth_date"} name={"birth_date"}/></dd>
                  </dl>
                  <button type={"submit"} name={"submit"} onSubmit={newClient}>Kurti</button>
              </form>
          </div>

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
