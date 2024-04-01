<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" @click="toggleNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <router-link class="navbar-brand" to="/home"><img src="https://img.freepik.com/vecteurs-premium/chat-femme-icone-vecteur-plat-entreprise-creative-probleme-solution-isolee_98396-63460.jpg" alt="" style="width: 35px;height: 35px;margin-top: -5px;"> SilenceForum</router-link>

        <div class="collapse navbar-collapse" :class="{ 'show': isNavbarOpen }">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link class="nav-link" to="/home"><img data-v-082ea05c="" src="https://www.iconpacks.net/icons/1/free-home-icon-189-thumb.png" alt="" style="width: 25px;height: 25px;margin-top: -5px;"> Acceuil</router-link>
            </li>
            <li v-if="isLoggedIn" class="nav-item">
              <router-link class="nav-link" to="/user-meal"><img src="https://cdn-icons-png.freepik.com/512/8463/8463836.png" alt="" style="width: 25px;height: 25px;margin-top: -5px;"> Mes publications</router-link>
            </li>
            <li v-if="isLoggedIn" class="nav-item">
              <router-link class="nav-link" to="/favoritemeals"><img src="https://cdn-icons-png.flaticon.com/512/7438/7438456.png" alt="" style="width: 25px;height: 25px;margin-top: -5px;"> Les publications recommandés</router-link>
            </li>
            <li v-if="isLoggedIn" class="nav-item">
              <router-link class="nav-link" to="/favoritemeals"><img src="https://static.vecteezy.com/system/resources/previews/011/175/666/original/user-experience-symbol-user-experience-icon-vector.jpg" alt="" style="width: 25px;height: 25px;margin-top: -5px;"> Les Experiences</router-link>
            </li>
          </ul>

          <div class="d-flex align-items-center">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li v-if="isLoggedIn" class="nav-item">
            <router-link class="nav-link" to="/profile">
                <button class="btn btn-outline-warning">
                    <i class="bi bi-person-check"></i> Profile
                </button>
            </router-link>
        </li>
    </ul>

    <button v-if="isLoggedIn" @click="logout" class="btn btn-outline-danger">
        <i class="bi bi-door-open-fill"></i> Logout
    </button>
</div>
        </div>
      </div>
    </nav>

   
    
    <div class="row mt-4">
    </div>
    <Toast />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useToast } from "primevue/usetoast";

const router = useRouter();
const isLoggedIn = localStorage.getItem('token');
const isNavbarOpen = ref(false);
const toast = useToast();

const toggleNavbar = () => {
  isNavbarOpen.value = !isNavbarOpen.value;
};

const logout = async () => {
  try {
    const token = localStorage.getItem('token');
    await axios.post('http://localhost:8000/api/logout', null, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    router.push("/login");
    toast.add({
      severity: "success",
      summary: "Logout Successful",
      detail: "You have been logged out",
      life: 3000,
    });
  } catch (error) {
    console.error(error);
    toast.add({
      severity: "error",
      summary: "Logout Error, Please Try Again",
      detail: "Something went wrong",
      life: 3000,
    });
  }
};
</script>

<style scoped>
.nav-item :hover{
  color: black;
  font-weight:600;
}
</style>
