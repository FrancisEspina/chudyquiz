<template>
  <v-container
    class="d-flex align-center justify-center"
    style="min-height: 70vh"
  >
    <div class="text-center">
      <v-icon size="100">mdi mdi-cat</v-icon>
      <h1 style="font-size: 3em">ChudyQUIZ</h1>

      <p style="font-size: 15pt">I Love You! Study well 🩷😘</p>
      <br />

      <div class="mx-auto">
        <v-row no-gutters>
          <v-col cols="12" lg="3" v-for="button in buttons" :key="button">
            <v-btn width="150px" height="45px" :to="button.path" class="ma-2">
              {{ button.name }}
            </v-btn>
          </v-col>
          <v-col cols="12" lg="3">
            <v-btn
              width="150px"
              height="45px"
              @click="dialogBox = true"
              class="ma-2"
            >
              Topics
            </v-btn>
          </v-col>
        </v-row>
      </div>
    </div>
    <v-dialog v-model="dialogBox" width="500px">
      <v-card>
        <div class="pa-6">
          <h3>Topics</h3>
          <br />

          <v-row class="d-flex text">
            <v-col cols="8">
              <h5 class="ms-3">Add Topic</h5>
              <v-text-field
                v-model="addSubject"
                density="default"
                label="Topic Name"
              ></v-text-field>
            </v-col>
            <v-col>
              <v-btn
                @click="addNewSubject(addSubject)"
                class="mt-7"
                append-icon="mdi mdi-check"
                color="green"
                variant="outlined"
                >ADD</v-btn
              >
            </v-col>
            <!-- remvoe -->
            <v-col cols="8">
              <h5 class="ms-3">Remove Topic</h5>

              <v-select
                label="Select Topic"
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
                class="mt-7"
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

        {
          name: "Review",
          path: "review",
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
