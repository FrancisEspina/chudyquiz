<template>
  <v-container>
    <div class="text-center">
      <v-icon size="40">mdi mdi-cat</v-icon>
      <h1 style="font-size: 24px">ChudyQUIZ</h1>
      <br />
    </div>
    <v-btn
      variant="outlined"
      prepend-icon="mdi mdi-arrow-left"
      color="white"
      :to="'/'"
      >Home</v-btn
    >
    <div class="mt-5">
      <v-row>
        <v-col>
          <v-select
            class="float-start me-5"
            width="300px"
            label="Select a Subject"
            v-model="selectedSubject"
            :items="subjects"
            item-title="name"
            item-value="name"
            variant="outlined"
          >
          </v-select>
          <v-text-field
            class="float-start"
            width="300px"
            density="default"
            label="Items"
            placeholder="(Min of 5 items)"
            :min="5"
            type="number"
          >
          </v-text-field>
        </v-col>
      </v-row>
    </div>

    <div class="rounded-xl ma-auto" style="height: 600px">
      <v-card style="max-width: 1200px" class="ma-auto mt-16" variant="tonal">
        <div class="pa-10 text-center">
          <h1>QUESTION #1</h1>
          <br />
          <div style="font-size: 18pt" id="question">
            Maxime magni hic tortor. Mollitia cumque magni quibusdam ea
            suspendisse, nam earum torquent phasellus, animi cumque aliquid
          </div>

          <div class="mt-10" id="choices">
            <v-row gutters>
              <v-col
                v-for="(label, index) in labels.slice(0, max)"
                :key="index"
                cols="12"
                sm="6"
              >
                <div class="mx-2">
                  <v-card @click="console.log(label)">
                    <div class="pa-5">
                      <v-chip class="bg-orange float-start mb-5"
                        ><b>{{ label }}</b></v-chip
                      >
                      {{ label }}
                    </div>
                  </v-card>
                </div>
              </v-col>
            </v-row>
          </div>
        </div>
      </v-card>
    </div>
  </v-container>
</template>
<script>
import {
  createQuestion,
  getQuestions,
  removeQuestion,
} from "../router/api/questionRoutes.js"; // Adjust the path as needed
import { getSubjects } from "../router/api/subjectRoutes.js"; // Adjust the path as needed

export default {
  data() {
    return {
      max: 4,
      labels: ["A", "B", "C", "D", "E", "F"], // Define your labels here

      selectedSubject: null,
      subjects: [],
    };
  },

  mounted() {
    this.getAllSubjects();
  },
  methods: {
    async getAllSubjects() {
      let response = await getSubjects();
      console.log(response);

      this.subjects = response.data;
    },
  },
};
</script>
