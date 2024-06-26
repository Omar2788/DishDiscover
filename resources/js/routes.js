
import Login from "./components/auth/login.vue"
import Accueil from './components/accueil.vue';
import Register from "./components/auth/Register.vue"
import ForgotPassword from "./components/auth/ForgotPassword.vue"
import Home from "./components/Home/Home.vue"
import Sidebar from "./components/sidebar/Sidebar.vue"
import UserMeal from "./components/Meal/UserMeal.vue"
import FavoriteMeals from "./components/Meal/FavoriteMeals.vue"
import MealDetails from "./components/Meal/MealDetails.vue"
import Profile from "./components/auth/Profile.vue"
export const routes=[
  
    // {
    //     name: 'accueil',
    //     path: '/',
    //     component: Accueil
    //     },
    {
        name:"login",
        path:"/login",
        component: Login
    },
    {
        name:"register",
        path:"/register",
        component:Register
    },
    {
    name:"ForgotPassword",
    path:"/forgot-password",
    component:ForgotPassword
    },
    {
        name:"bar",
        path:"/bar",
        component:Sidebar
        },
    {
        name: 'home',
        path: '/home',
        component: Home,
        meta:{isAuth:true}
      },
      {
       
    name: 'user-meal',
    path: '/user-meal',
        component: UserMeal,
        meta: { isAuth: true }, // Add this if authentication is required
      },
      {
       
        name: 'favoritemeals',
        path: '/favoritemeals',
            component: FavoriteMeals,
            meta: { isAuth: true }, // Add this if authentication is required
          },
          {
            name: 'mealDetails',
            path: '/mealDetails/:id',
            component: MealDetails,
            meta: { isAuth: true },
          },
          {
            name:"profile",
            path:"/profile",
            component:Profile,
            meta: { isAuth: true },
          }

]