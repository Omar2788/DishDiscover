<template>
  <Sidebar />
  <div>
    <div class="headUserMeals">
      <h4>Vos Publications.</h4>
      <p>Ce sont les publications que vous avez publier, Explorez-les, modifiez-les ou supprimez-les selon vos besoins. </p>
    </div>
      <div class="row" v-if="userMeals.length > 0">
          <div class="col-md-6" v-for="meal in userMeals" :key="meal.id">
              <Card
                  class="card"
                  style="
                      width: 30em;
                      margin-bottom: 1rem;
                      margin-left: auto;
                      margin-right: auto;
                      height: 450px;">
                  
                  <template #title>{{ meal.title }}</template>
                  <template #content>
                      <p class="m-0">{{ meal.description }}</p>
                  </template>
                  <template #footer>
                      <i
                          class="bi bi-pencil edit-icon"
                          @click="editMeal(meal)"
                      ></i>
                      <i class="bi bi-trash delete-icon"
                          @click="deleteMeal(meal.id)"></i>
                      <hr>
                      <div class="created-at"><i class="bi bi-calendar3"></i> Créé à : {{ formatDate(meal.created_at) }}</div>
                  <div class="updated-at"><i class="bi bi-arrow-repeat update-icon"></i> Modifié à : {{ formatDate(meal.updated_at) }}</div>
                  </template>
              </Card>
          </div>
          <Toast />
      </div>
      <div v-else>
          <p>No meals available. Start adding meals!</p>
      </div>
      <Dialog v-if="isEditDialogVisible" :visible.sync="isEditDialogVisible">
     <div class="dialog">
        <div class="row">
          <div class="col-md-6">
            <h4>Modifier publication</h4>
          </div>
          
        </div>
        <div class="row">
          <form @submit.prevent="saveEditedMeal">
            <div class="row">
              <div class="col-md-6">
                  <label for="editTitle">Titre de la publication :</label>
                  <br>
                  <input v-model="selectedMeal.title" type="text" id="editTitle" />
              </div></div>
              <div class="row">
              <div class="col-md-6">
                  <label for="editDescription">Description de la publication :</label>
                  <textarea v-model="selectedMeal.description" id="editDescription" class="large-textarea"></textarea>
              </div></div>
             <!--  <div class="row">
                <span>You can insert new picture to change the Meal picture :</span>
            <file-pond
              name="mealImage"
              ref="pond"
              class-name="my-pond"
              label-idle="Add New picture here..."
              allow-multiple="false"
              accepted-file-types="image/jpeg, image/png"
              v-bind:files="myFiles"
              v-on:init="handleFilePondInit"
              :server="serverOptions()"
            />
          </div> -->
            <hr>
              <div class="col-md-6">
                  <Button type="button" label="Cancel" @click="cancelEdit" class="p-button-secondary" />
                  <Button type="submit" label="Save" class="p-button-success"></Button>
                  
              </div>
          </form>
        </div>
      </div>
      </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Card from "primevue/card";
import Sidebar from "../sidebar/Sidebar.vue";
import { useToast } from "primevue/usetoast";
import Dialog from "primevue/dialog";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
const FilePond = vueFilePond(FilePondPluginImagePreview);
const toast = useToast();
const userMeals = ref([]);
const isLoading = ref(true);
const token = localStorage.getItem("token");
const isEditDialogVisible = ref(false);
const selectedMeal = ref(null);
const meal = ref({ image: "" });
const myFiles = ref([]);


const getUserMeals = async () => {
  try {
      const response = await axios.get("/api/userMeal", {
          headers: {
              Authorization: `Bearer ${token}`,
          },
      });
      userMeals.value = response.data;
      isLoading.value = false;
  } catch (error) {
      console.error(error);
  }
};

const editMeal = (meal) => {
selectedMeal.value = meal;
isEditDialogVisible.value = true;
};

const deleteMeal = async (id) => {
  try {
      await axios.delete(`/api/deleteuserMeal/${id}`, {
          headers: {
              Authorization: `Bearer ${token}`,
          },
      });

      await toast.add({
          severity: "success",
          summary: "Publication supprimée avec succès",
          life: 5000,
      });
      console.log("Publication deleted successfully");
      getUserMeals();
  } catch (error) {
      console.error(error);
  }
};

const saveEditedMeal = async () => {
  try {
      
          const authToken = localStorage.getItem('token'); // Adjust based on how you store your token
         await axios.put(`/api/meal/${selectedMeal.value.id}`, selectedMeal.value, {
     headers: {
        Authorization: `Bearer ${authToken}`,
      },
    });

          await toast.add({
              severity: "success",
              summary: "Publication modifiée avec succès",
              life: 5000,
          });

          console.log("Publication updated successfully");
          getUserMeals();
          isEditDialogVisible.value = false;
       
  } catch (error) {
      console.error(error);
  }
};

const handleFilePondInit = async() => {
     
     if (selectedMeal.value.image) {
     
       myFiles.value = [
     {
       source: selectedMeal.value.image,
       options: { type: 'local' }
     }
     ]
    }
     }
const serverOptions = () => { 
return {
    load: (source, load, error, progress, abort, headers) => {
                    var myRequest = new Request(source);
                    fetch(myRequest).then(function(response) {
                      response.blob().then(function(myBlob) {
                        load(myBlob);
                      });
                    });
                  },
process: (fieldName, file, metadata, load, error, progress, abort) => {
const data = new FormData();
data.append('file', file);
data.append('upload_preset', 'dischdiscover_cloudinary');
data.append('cloud_name', 'dfwtjbdrv');
data.append('6cd43ddcc1a5e3927a5a5e5e28f4ad', file.name);
axios.post('https://api.cloudinary.com/v1_1/dfwtjbdrv/upload',data)
.then((response) => response.data)
.then((data) => {
console.log(data);

selectedMeal.value.image = data.url;
load(data);
})
.catch((error) => {
console.error('Error uploading file:', error);
error('Upload failed');
abort();
});
},
};
};

const cancelEdit = () => {
  isEditDialogVisible.value = false;
};

onMounted(() => {
  getUserMeals();
});

const formatDate = (dateTimeString) => {
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    
  };
  return new Date(dateTimeString).toLocaleDateString(undefined, options);
};

</script>

<style lang="css" scoped>
.card {
  margin-top: 2rem;
  border-radius: 15px;
  transition: 0.5s;
}

.card:hover {
  transform: scale(1.02);
}

.edit-icon,
.delete-icon {
  font-size: 1.5rem;
  cursor: pointer;
  margin-right: 0.5rem; 
}
.edit-icon {
  color: #007bff; 
}
.delete-icon {
  color: #dc3545; 
}
.dialog {
  width: 500px;
}
.dialog .row {
  margin-bottom: 10px;
}
.dialog button {
  margin-right: 10px;
}
.dialog label{
  margin-bottom: 10px;
}
.imagemeal:hover{
  transform: scale(1.1);
  transition: 0.5s;
}
.p-card-content {
  max-height: 10px;

}
.large-textarea {
  min-height: 100px;
  max-height: 200px;
width: 200%;
}
.headUserMeals{
  margin-left: 10%;
  margin-right: 10%;
  margin-top: 5%;
  text-align: center;
  color: rgb(59, 0, 59);
  font-size: 1.5rem;
  cursor: pointer;
  border-bottom: 2px solid #019176;

}
</style>
