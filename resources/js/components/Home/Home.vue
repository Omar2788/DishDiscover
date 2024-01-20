<template>
  <div class="row">
    <Sidebar />
    <div class="description">
      <img src="https://img.cuisineaz.com/1200x675/2024/01/14/i197127-.webp" alt="" class="imagemeal">
      <p>What if you cooked a warm and comforting gratin for dinner? This is a great way to start the week smoothly, knowing that you will end the day with a steaming plate of healthy comfort food that is easy to prepare in ten minutes!</p>
    </div>
    <h2>DISH IDEAS FOR TODAY</h2>
    <AddMeal />
   
    <div class="col-md-6" v-for="meal in meals" :key="meal.id">
      <Card class="card" style="width: 30em; margin-bottom: 1rem; margin-left: auto; margin-right: auto;height: 650px;">
        <template #header>
          <img :src="meal.image" :alt="meal.title" class="shadow-4 imagemeal" style="width: 100%; max-height: 280px;" />
        </template>
        <template #title>{{ meal.title }}</template>
        <template #content>
          <p class="m-0">{{ meal.description }}</p>
        </template>
        
        <template #footer>
          <i class="bi bi-star star" :style="{ color: meal.isFavorite ? 'gold' : 'rgb(59, 0, 59)' }" @click="addToFavorite(meal)"></i> <span>Add to Favorites</span>
          <hr>
          <div class="created-at"><i class="bi bi-calendar3"></i> Created at : {{ formatDate(meal.created_at) }}</div>
          <i class="bi bi-clock"></i> {{ formatTime(meal.created_at) }}
          <br>
          <div class="rlink">
           <router-link :to="{ name: 'mealDetails', params: { id: meal.id } }">More Details</router-link>
          </div>
        </template>
      </Card>
    </div>
    <Toast />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AddMeal from "../Meal/addMeal.vue";
import axios from 'axios';
import Card from 'primevue/card';
import 'bootstrap/dist/css/bootstrap.min.css';
import "bootstrap-icons/font/bootstrap-icons.css";
import Sidebar from "../sidebar/Sidebar.vue";
import { useToast } from "primevue/usetoast";


const toast = useToast();


const meals = ref([]);
const isLoading = ref(true);


const getMeals = async () => {
  try {
    const response = await axios.get("/api/meal");
    meals.value = response.data;
    isLoading.value = false;
  } catch (error) {
    console.error(error);
  }
};

const authToken = localStorage.getItem('token');

const addToFavorite = async (meal) => {
  try {
    const isFavorite = meal.isFavorite;

    if (isFavorite) {
      // Remove from favorites
      await axios.delete(`/api/favoritemeals/${meal.id}`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });
    } else {
      // Add to favorites
      await axios.post("/api/favoritemeals", { meal_id: meal.id }, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });
    }

    meal.isFavorite = !isFavorite;

    toast.add({
      severity: "success",
      summary: isFavorite ? "Meal removed from favorites" : "Meal added to favorites",
      life: 5000,
    });
  } catch (error) {
    console.error(error.response);
    toast.add({
      severity: "error",
      summary: "Error toggling favorite status",
      life: 5000,
    });
  }
};

onMounted(() => {
  getMeals();
  setFavoriteStatus();
});

const setFavoriteStatus = async () => {
  try {
    const response = await axios.get("/api/favoritemeals", {
      headers: {
        'Authorization': `Bearer ${authToken}`
      }
    });

    const favoriteMealIds = response.data.map(favorite => favorite.meal_id);

    // Set isFavorite property for each meal based on the database
    meals.value.forEach(meal => {
      meal.isFavorite = favoriteMealIds.includes(meal.id);
    });

  } catch (error) {
    console.error(error);
  }
};
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};
const formatTime = (dateTimeString) => {
  const options = { hour: 'numeric', minute: 'numeric'};
  return new Date(dateTimeString).toLocaleTimeString(undefined, options);
};
</script>

<style lang="css" scoped>
.card-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.card {
  margin-top: 2rem;
  border-radius: 15px;
  transition: 0.5s;
  width: 30em;
  margin-bottom: 1rem;
  margin-left: auto;
  margin-right: auto;
}

.p-card-content {
  overflow-y: auto; /* Add overflow-y property for vertical scroll */
  max-height: 10px;

}

.card:hover {
  transform: scale(1.02);
}

.star {
  color: rgb(59, 0, 59);
  font-size: 1.5rem;
  cursor: pointer;
}
h2{
  text-align: center;
  margin-top: 2rem;
  margin-bottom: 2rem;
  letter-spacing: 0.3rem;
  color: rgb(59, 0, 59);
  font-size: 1.5rem;
  cursor: pointer;
  border-bottom: 2px solid rgb(173, 1, 173);
  width: 50%;
  margin-left: auto;
  margin-right: auto;
}
.description{
  display: flex;
  justify-content: space-between; 

}
.description img{
  width: 90%;
  height: 400px;
  margin-top: 2rem;
  margin-left: 2rem;
  margin-right: 2rem;
  border-radius: 10px;
}
.description p{
  width: 90%;
  margin-top: 2rem;
  margin-left: 2rem;
  margin-right: 2rem;
  font-size: 1.2rem;
  color: rgb(59, 0, 59);
  font-weight: 600;
  
}
.card p {
  max-height: 100px; /* Set the maximum height */
  overflow-y: auto;
}
span{
  font-size: 0.9rem;
  color: rgb(59, 0, 59);
  font-weight: 600;
}
.imagemeal{
  cursor: pointer;
 
}
.imagemeal:hover{
  transform: scale(1.1);
  transition: 0.5s;
}
.rlink a{
  color: rgb(59, 0, 59); /* Set the desired color */
  cursor: pointer;
  float: right;
  font-weight: 600;
}

.rlink:hover {
  color: red; /* Set the desired hover color */
}

</style>