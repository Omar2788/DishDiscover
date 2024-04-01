<template>
  <div class="row">
    <Sidebar />
    <div class="description">
      <!-- <img src="https://image.brigitte.de/11768994/t/BA/v3/w1440/r1.5/-/probleme-mit-chef.jpg" alt="" class="imagemeal">
      <p>What if you cooked a warm and comforting gratin for dinner? This is a great way to start the week smoothly, knowing that you will end the day with a steaming plate of healthy comfort food that is easy to prepare in ten minutes!</p> -->
      <p>Reprenez le <i>contrôle</i> de votre vie</p>
      <a>La première plateforme exclusive en Tunisie conçue pour publier et discuter des solutions à vos problèmes.</a>
    </div>
    <h2>Partagez avec notre communaute</h2>
    <template v-if="userRole === 'admin'">
      <AddExperience />
    </template>
    <template v-else>
      <AddMeal />
    </template>
   
    <div class="col-md-6" v-for="meal in meals" :key="meal.id">
      <Card class="card" style="width: 30em; margin-bottom: 1rem; margin-left: auto; margin-right: auto;height: 450px;">
        <template #title>{{ meal.title }}</template>
        <template #content>
          <p class="m-0">{{ meal.description }}</p>
        </template>
        
        <template #footer>
          <div class="reactionSection d-flex justify-content-between align-items-center">
      <div>
        <i class="bi bi-hand-thumbs-up-fill star" :style="{ color: meal.isLiked ? 'blue' : 'rgb(59, 0, 59)' }" @click="toggleLike(meal)"></i>
        <span>{{ meal.isLiked ? 'Liked' : 'Like' }}</span>
        <i class="bi bi-hand-thumbs-down-fill star" :style="{ color: meal.isDisliked ? 'red' : 'rgb(59, 0, 59)' }" @click="toggleDislike(meal)"></i>
        <span>{{ meal.isDisliked ? 'Disliked' : 'Dislike' }}</span> 
      </div>
      <router-link :to="{ name: 'mealDetails', params: { id: meal.id } }" class="comment-link"><i class="bi bi-chat-left"></i> <span class="comment-text">Commentaire</span></router-link>
    </div>
    <hr>
          <div class="created-at"><i class="bi bi-calendar3"></i> Created at : {{ formatDate(meal.created_at) }}</div>
          <i class="bi bi-clock"></i> {{ formatTime(meal.created_at) }}
          <br>
          <div class="rlink">
           <router-link :to="{ name: 'mealDetails', params: { id: meal.id } }">Plus Détails</router-link>
          </div>
        </template>
      </Card>
    </div>
    <Toast />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AddMeal from "../Meal/AddMeal.vue";
import AddExperience from "../Experience/AddExperience.vue";
import axios from 'axios';
import Card from 'primevue/card';
import 'bootstrap/dist/css/bootstrap.min.css';
import "bootstrap-icons/font/bootstrap-icons.css";
import Sidebar from "../sidebar/Sidebar.vue";
import { useToast } from "primevue/usetoast";


const toast = useToast();
const userRole = ref('');

const meals = ref([]);
const isLoading = ref(true);

const fetchUserRole = async () => {
  try {
    const response = await axios.get("/api/userRole", {
      headers: {
        'Authorization': `Bearer ${authToken}`
      }
    });
    userRole.value = response.data.role; // Assign fetched role to userRole ref
    console.log(userRole.value);
  } catch (error) {
    console.error(error);
  }
};


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

const toggleLike = async (meal) => {
  try {
    const isLiked = meal.isLiked;

    if (isLiked) {
      // Remove like
      await axios.delete(`/api/meal/like/${meal.id}`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });
    } else {
      // Add like
      await axios.post("/api/favoritemeals", { meal_id: meal.id }, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });
    }

    meal.isLiked = !isLiked;
    meal.isDisliked = false;

    toast.add({
      severity: "success",
      summary: isLiked ? "Like removed" : "Liked",
      life: 5000,
    });
  } catch (error) {
    console.error(error.response);
    toast.add({
      severity: "error",
      summary: "Error toggling like status",
      life: 5000,
    });
  }
};

const toggleDislike = async (meal) => {
  try {
    const isDisliked = meal.isDisliked;

    if (isDisliked) {
      // Remove dislike
      await axios.delete(`/api/favoritemeals/${meal.id}`, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });
    } else {
      // Add dislike
      await axios.post("/api/favoritemeals", { meal_id: meal.id }, {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });
    }

    meal.isDisliked = !isDisliked;
    meal.isLiked = false;

    toast.add({
      severity: "success",
      summary: isDisliked ? "Dislike removed" : "Disliked",
      life: 5000,
    });
  } catch (error) {
    console.error(error.response);
    toast.add({
      severity: "error",
      summary: "Error toggling dislike status",
      life: 5000,
    });
  }
};

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
  fetchUserRole();
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
      meal.isLiked = favoriteMealIds.includes(meal.id);
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
.comment-link {
  color: #007bff; /* Change to your desired color */
  text-decoration: none; /* Remove default underline */
  cursor: pointer;
}

.comment-link:hover {
  color: #0056b3; /* Change to your desired hover color */
}

.comment-text {
  font-weight: bold; /* Optionally make the text bold */
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
  color: #233834;
  font-weight: 600;
  border-bottom: 2px solid #019176;
  width: 50%;
  margin-left: auto;
  margin-right: auto;
}
.description{
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
  margin-top: 10rem;
  margin-left: auto;
  margin-right: auto;
  font-size:6rem;
  font-weight: 700;
  width: 75%;
  text-align: center;
  color: #555858;
  line-height: 90%;
  margin-bottom: 20px;
}
.description i{
  color: #233834;
}
.description a{
  font-size:1.5rem;
  font-weight: 500;
  width: 75%;
  text-align: center;
  color: #233834;
margin-left: 150px;

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

</style>../Experience/AddExperience.vue