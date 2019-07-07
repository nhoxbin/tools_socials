import axios from 'axios';

const webService = axios.create({
	baseURL: 'http://localhost:8000/api/auth'
});

export default webService;