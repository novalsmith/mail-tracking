import axios from 'axios';
import router from '../router'
// const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InNhbXBsZUBnbWFpLmNvbSIsImlhdCI6MTY2NDY4MjYxNiwiZXhwIjoxNjY0Njg0MDU2fQ.IN-cZpponpcOPWTiIuP--HBImWl9phCwDNFG_TThmro';
// console.log('form http-component');
// console.log(localStorage.getItem('token'));
// if(localStorage.getItem('token')!="")
// axios.defaults.baseURL = process.env.VUE_APP_SERVICE_URL;
// axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
// axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
// axios.create({
//     baseURL: process.env.VUE_APP_SERVICE_URL,
//     headers: {
//         Authorization: `Bearer ${localStorage.getItem('token')}`
//     }
// });

// let refresh = false;

// axios.interceptors.request.use(
//     function(config) {
//       // Do something before request is sent
//       const token = localStorage.getItem('token');
//       if (token) {
//         axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
//       }
//       console.log(config);
//       return config;
//     },
//     function(error) {
//       // Do something with request error
//       return Promise.reject(error);
//     }
//   );

//     // Add a response interceptor
//     axios.interceptors.response.use(
//         function(response) {
//           // Any status code that lie within the range of 2xx cause this function to trigger
//           // Do something with response data
//           return response;
//         },
//         async function(error) {
//           // Any status codes that falls outside the range of 2xx cause this function to trigger
//           // Do something with response error
//           const originalRequest = error.config;
//           console.log(error.response.data['error']);
//           if (error.response.status === 401 && error.response.data['error'] === "Expired token") {
//             originalRequest._retry = true;
//             const param = {
//                 "email": "sample@gmai.com",
//                 "password": "sample"
//             }
//             const {status, data} = await axios.post('otentikasi', param, {
//                 withCredentials: true
//             });
//             console.log(data.access_token);
//             if (status === 200) {
//                 localStorage.setItem('token', data.access_token); 
//                 axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
//                 return Promise.reject(error);
//             }
//           }
//           originalRequest._retry = false;
//           return Promise.reject(error);
//         }
//       );
// axios.interceptors.response.use(resp => resp, async error => {
//     if (error.response.status === 401 && !refresh) {

//         if (error.response.data && error.response.data.error === "Expired token") {
//             refresh = true;
//             const param = {
//                 "isWebAdmin": true
//             }
//             const {
//                 status,
//                 data
//             } = await axios.post('otentikasi', param, {
//                 withCredentials: true
//             });
//             console.log(data.access_token);
//             if (status === 200) {
//                 localStorage.setItem('token', data.access_token);
// axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
//                 axios.create({
//                     baseURL: process.env.VUE_APP_SERVICE_URL,
//                     headers: {
//                         Authorization: `Bearer ${localStorage.getItem('token')}`
//                     }
//                 });
//                 console.log(error);
//                 return axios(error.config);
//             } else {
//                 return Promise.reject(error);
//             }
//         }
//     } else {
//         refresh = false;

//         return Promise.reject(error);
//     }
// });
axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
axios.interceptors.response.use(resp => resp, async error => {
    if (error.response.status === 401) {
        router.push('/logout')
    } else {
        return Promise.reject(error);
    }
});