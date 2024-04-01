<template>
    <div class="profile-container">
           <Sidebar />
         <div class="profile-card">
           <div class="card-header">Profile Management</div>
           <div class="card-body">
             <form @submit.prevent="updateProfile">
               <div class="form-group">
                 <label for="name" class="form-label"><i class="fa fa-user"></i>Name</label>
                 <input id="name" type="text" class="form-control" v-model.trim="user.name" required autofocus autocomplete="off" />
               </div>
               <div class="form-group">
               <label for="address" class="form-label">Address</label>
               <input id="address" type="text" class="form-control" v-model.trim="user.adresse" required autocomplete="off" />
             </div>
             <div class="form-group">
               <label for="telephone" class="form-label">Telephone</label>
               <input id="telephone" type="text" class="form-control" v-model.trim="user.telephone" required autocomplete="off" />
             </div>

               <div class="form-group">
                   <div v-if="emailExists" class="error-message">This email is already taken.</div>

                 <label for="email" class="form-label">E-Mail Address</label>
                 <input id="email" type="email" class="form-control" v-model.trim="user.email" required autocomplete="off" />
               </div>

               <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>

               <div class="form-group mt-3">
                 <button type="submit" class="btn btn-primary">Update Profile</button>
                 <router-link to="/home" class="btn btn-warning">Go to Home</router-link>

               </div>

             </form>

           </div>
         </div>
         <Toast />
       </div>
     </template>
   <script setup>
   import axios from 'axios';
   import { useRouter } from 'vue-router';
   import { useToast } from "primevue/usetoast";
   import { ref, onMounted } from 'vue';
   import Sidebar from "../sidebar/Sidebar.vue";

   const router = useRouter();
   const toast = useToast();
   const emailExists = ref(false);

   const user = ref({});
   const errorMessage = ref('');

   onMounted(() => {
     const registeredUser = localStorage.getItem('registeredUser');
     if (registeredUser) {
       user.value = JSON.parse(registeredUser);
     }
   });
   const updateProfile = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.put('http://localhost:8000/api/profile', user.value, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    console.log(response);
    // Mettre à jour les données de l'utilisateur dans le local storage
    localStorage.setItem('registeredUser', JSON.stringify(user.value));

    toast.add({
      severity: "success",
      summary: "Profile Updated",
      detail: "Your profile has been successfully updated.",
      life: 3000,
    });
  } catch (error) {
    errorMessage.value = error.response.data.message;
    console.log(error.response.data);
    toast.add({
      severity: "error",
      summary: "Update Failed",
      detail: "Failed to update profile. Please try again.",
      life: 3000,
    });
  }
};

   const checkEmailExists = async (email) => {
     try {
       const response = await axios.get(`http://localhost:8000/api/check-email/${email}`);
       return response.data.exists;
     } catch (error) {
       console.error(error);
       return false;
     }
   };

   const handleSubmit = async () => {
     if (await checkEmailExists(user.value.email)) {
       emailExists.value = true;
       return;
     }

     await updateProfile();
   };
   </script>
   <style scoped>
   /* Styles for form group */
   .form-group {
       margin-bottom: 20px;
   }
   .error-message {
     color: red;
     margin-top: 5px;
   }
   /* Styles for form label */
   .form-label {
       display: block;
       margin-bottom: 8px;
       font-weight: bold;
   }

   /* Styles for form control */
   .form-control {
       width: 100%;
       padding: 10px;
       font-size: 16px;
       border: 1px solid #ccc;
       border-radius: 4px;
       transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
   }

   /* Styles for form control focus */
   .form-control:focus {
       outline: none;
       border-color: #3490dc;
       box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
   }

   /* Styles for primary button */
   .btn-primary {
       background-color: #3490dc;
       color: #fff;
       padding: 12px 20px;
       border: none;
       border-radius: 4px;
       cursor: pointer;
   }

   /* Styles for primary button hover */
   .btn-primary:hover {
       background-color: #2779bd;
   }

   /* Styles for warning button */
   .btn-warning {
       background-color: #ffc107;
       color: #212529;
       padding: 12px 20px;
       border: none;
       border-radius: 4px;
       cursor: pointer;
   }

   /* Styles for warning button hover */
   .btn-warning:hover {
       background-color: #e0a800;
   }
   </style>