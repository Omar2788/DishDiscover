<template>
  <div class="register-container">
    <div class="register-card">
      <div class="card-header">Register</div>
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input
              id="name"
              type="text"
              class="form-control"
              v-model.trim="user.name"
              required
              autofocus
              autocomplete="off"
            />
          </div>
          <div class="form-group">
          <label for="selectedItems" class="form-label">Choisir les critères :</label>
          <MultiSelect id="selectedItems" v-model="selectedItem" :options="critere" optionLabel="name" placeholder="les critères"
              :maxSelectedLabels="3" class="w-full md:w-20rem" />
        </div>

          <div class="form-group">
            <label for="email" class="form-label">E-Mail Address</label>
            <input
              id="email"
              type="email"
              class="form-control"
              v-model.trim="user.email"
              required
              autocomplete="off"
            />
          </div>
          <div class="telAdresse">
          <div class="form-group">
          <label for="adresse" class="form-label">Adresse</label>
          <input
            id="adresse"
            type="text"
            class="form-control"
            v-model.trim="user.adresse"
            required
            autocomplete="off"
          />
        </div>
      
        <div class="form-group">
          <label for="telephone" class="form-label">Telephone</label>
          <input
            id="telephone"
            type="text"
            class="form-control"
            v-model.trim="user.telephone"
            required
            autocomplete="off"
          />
        </div>
      </div>
          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input
              id="password"
              type="password"
              class="form-control"
              v-model.trim="user.password"
              required
              autocomplete="off"
            />
          </div>
          <div class="form-group">
            <label for="password_confirmation" class="form-label">Password Confirmation</label>
            <input
              id="password_confirmation"
              type="password"
              class="form-control"
              v-model.trim="user.password_confirmation"
              required
              autocomplete="off"
            />
          </div>
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">
              Register
            </button>
          </div>
        </form>
        <div class="auth-switch-link">
          Already have an account? <router-link to="/login">Login here</router-link>
        </div>
      </div>
    </div>
    <Toast />
  </div>
</template>

<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useToast } from "primevue/usetoast";
import { ref } from "vue";
import MultiSelect from 'primevue/multiselect';

const router = useRouter();
const toast = useToast();

const selectedItem = ref([]);

const critere = [
  { name: 'Probleme familiale' , value:'problemefamiliale'},
  { name: 'Probleme social', value:'problemesocial'  },
  { name: 'Probleme de santé',value:'problemedesante' },
  { name: 'Probleme de travail',value:'problemedetravail' },
  { name: 'Probleme de couple',value:'problemedecouple' },
  { name: 'Probleme de communication',value:'problemedecommunication' },
  { name: 'Probleme de education',value:'problemedeeducation' },
];

let user = {
  name: "",
  selectedItems: [],
  email: "",
  password: "",
  password_confirmation: "",
  adresse: "", // Nouveau champ pour l'adresse
  telephone: "", // Nouveau champ pour le téléphone
};

const handleSubmit = async () => {
try {
  user.selectedItems = selectedItem.value.map(item => item.value); // Mettre à jour l'objet utilisateur avec les éléments sélectionnés
  const response = await axios.post('http://localhost:8000/api/register/', user);
  console.log(response);
  localStorage.setItem('registeredUser', JSON.stringify(user));
  router.replace({ name: 'login' });
  toast.add({
    severity: "success",
    summary: "Registration successful",
    detail: "You can now login",
    life: 3000,
  });
} catch (error) {
  console.log(error.response.data);
  toast.add({
    severity: "error",
    summary: "Registration failed, please try again.",
    detail: error.response.data.message,
    life: 3000,
  });
}
};

</script>

<style scoped>
.register-container {
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.register-card {
  width: 400px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.telAdresse{
  display: flex;
  justify-content: space-between;

}
.card-header {
  padding: 10px;
  background-color: #3490dc;
  color: #fff;
  text-align: center;
}

.card-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.btn-primary {
  background-color: #3490dc;
  color: #fff;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary:hover {
  background-color: #2779bd;
}

.auth-switch-link {
  text-align: center;
  margin-top: 15px;
}

.auth-switch-link a {
  color: #3490dc;
  text-decoration: underline;
  cursor: pointer;
}
</style>