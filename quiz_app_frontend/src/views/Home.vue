<template>
  <v-container
    class="d-flex align-center justify-center"
    style="min-height: 80vh"
  >
    <div class="text-center">
      <v-icon size="100">mdi mdi-cat</v-icon>
      <h1 style="font-size: 90px">ChudyQUIZ</h1>

      <div class="mx-auto">
        <v-btn
          v-for="button in buttons"
          :key="button"
          :to="button.path"
          class="mx-3"
        >
          {{ button.name }}
        </v-btn>

        <v-btn @click="dialogBox = true" class="mx-3"> Subjects </v-btn>
      </div>
    </div>
    <v-dialog v-model="dialogBox" width="500px">
      <v-card>
        <div class="pa-6">
          <h3>Subjects</h3>
          <br />
          <v-row class="d-flex">
            <v-col cols="8">
              <v-text-field
                v-model="addSubject"
                density="default"
                label="Subject Name"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-btn
                @click="addNewSubject(addSubject)"
                class="mt-3"
                append-icon="mdi mdi-check"
                color="green"
                variant="outlined"
                >ADD</v-btn
              >
            </v-col>
            <!-- remvoe -->
            <v-col cols="8">
              <v-select
                label="Select Subject"
                v-model="removeSubject"
                variant="outlined"
                :items="subjects"
                item-title="name"
                item-value="id"
              ></v-select>
            </v-col>
            <v-col>
              <v-btn
                @click="removeSelectedSubject(removeSubject)"
                class="mt-3"
                append-icon="mdi mdi-close"
                color="red"
                variant="outlined"
                >Remove</v-btn
              >
            </v-col>
          </v-row>
        </div>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import {
  getSubjects,
  addNewSubject,
  removeSubject,
} from "../router/api/subjectRoutes.js"; // Adjust the path as needed

export default {
  data() {
    return {
      subjects: [],
      addSubject: "",
      removeSubject: null,
      dialogBox: false,
      is_Ready: true,
      buttons: [
        {
          name: "Add Questions",
          path: "questions",
        },
        {
          name: "Take Test",
          path: "test",
        },
      ],
    };
  },

  mounted() {
    this.getAllSubjects();
  },

  methods: {
    async addNewSubject(name) {
      let response = await addNewSubject(name);
      console.log(response);
      this.getAllSubjects();
      this.addSubject = "";
    },

    async removeSelectedSubject(subject) {
      console.log("from remove", subject);
      let response = await removeSubject(subject);
      console.log(response);
      this.getAllSubjects();
      this.removeSubject = null;
    },

    async getAllSubjects() {
      let response = await getSubjects();
      console.log(response);

      this.subjects = response.data;
    },
  },
};
</script>
