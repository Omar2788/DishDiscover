<template>
    <div>

      <button type="button"  class="btn rounded-circle new-button" @click="visible = true">
        <span style="color: rgb(43, 27, 27)">
          <i class="bi bi-plus-circle" style="color: white;" ></i>
        </span>
        Ajouter Experience
      </button>
      <form>
       
          <Dialog v-model:visible="visible" >
            <div class="dialog">
            <div class="row">
              <div class="col-md-6">
                <label for="title" class="form-label">Titre de l'experience :</label>
                <input type="text" class="form-control" id="title" v-model="meal.title" />
              </div>
              </div>
              <div class="row">
              <div class="col-md-6">
                <label for="description" class="form-label">Description de l'experience :</label>
                <textarea type="text" class="form-control" id="description" v-model="meal.description" />
              </div>
            </div>
            <div class="row">
              <file-pond
                name="mealImage"
                ref="pond"
                class-name="my-pond"
                label-idle="Ajouter une image ici..."
                allow-multiple="false"
                accepted-file-types="image/jpeg, image/png"
                v-bind:files="myFiles"
                :server="serverOptions()"
              />
            </div>
            <hr>
            <br />
            <button type="submit" class="btn btn-outline-primary" @click="addMeal">
              <i class="bi bi-floppy"></i> Save
            </button>
            
            <button type="button" class="btn btn-outline-primary" @click="cancel">
              <i class="bi bi-x-lg"></i> Cancel
            </button> </div>
          </Dialog>
       
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from "vue";
  import vueFilePond from "vue-filepond";
  import "filepond/dist/filepond.min.css";
  import Dialog from "primevue/dialog";
  import FilePondPluginImagePreview from "filepond-plugin-image-preview";
  import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
  const FilePond = vueFilePond(FilePondPluginImagePreview);
  const myFiles = ref([]);
  import axios from "axios";
  const visible = ref(false);
  const meal = ref({
    title: "",
    description: "",
    image: "",
  });

  const addMeal = async () => {
  try {
    console.log(meal.value);
    const authToken = localStorage.getItem('token'); // Adjust based on how you store your token
    await axios.post("/api/meal", meal.value, {
      headers: {
        Authorization: `Bearer ${authToken}`,
      },
    });
   
    location.reload();
    console.log("Meal added successfully");
    visible.value = false;
  } catch (error) {
    console.log(error);
  }
};

  onMounted(() => {
    // Additional logic on component mount if needed
    
  });
  
  const handleFilePondInit = () => {
    console.log("FilePond has initialized");
  };
  
  const cancel = () => {
    visible.value = false;
  };
  
  const serverOptions = () => {
    return {
      process: (fieldName, file, metadata, load, error, progress, abort) => {
        const data = new FormData();
        data.append("file", file);
        data.append("upload_preset", "dischdiscover_cloudinary");
        data.append("cloud_name", "dfwtjbdrv");
        data.append("6cd43ddcc1a5e3927a5a5e5e28f4ad", file.name);
  
        axios.post("https://api.cloudinary.com/v1_1/dfwtjbdrv/upload", data)
          .then((response) => response.data)
          .then((data) => {
            meal.value.image = data.url;
            load(data);
          })
          .catch((error) => {
            console.error("Error uploading file:", error);
            error("Upload failed");
            abort();
          });
      },
    };
  };
  </script>
  
  <style scoped>
.new-button-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px; /* Adjust the margin as needed */
}
button {
  float: right;
  background-color: rgb(141, 147, 187);
  color: white;
  border-color: rgb(141, 147, 187);
}
button:hover {
  background-color: rgb(64, 74, 138);
  color: white;
  border-color: rgb(141, 147, 187);
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
</style>
  