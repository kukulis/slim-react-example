import { useState, useEffect } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
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

  return (
    <>
      <h1>Klientai</h1>
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
