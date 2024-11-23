<template>
  <v-container>
    <div class="text-center">
      <router-link
        class="text-decoration-none"
        :style="{ color: 'inherit' }"
        to="/"
      >
        <div>
          <h5>
            <v-icon size="25">mdi mdi-cat</v-icon>
            ChudyQUIZ
          </h5>
        </div>
      </router-link>
      <h1>Take Test</h1>
    </div>
    <div>
      <v-btn
        :color="null"
        variant="outlined"
        prepend-icon="mdi mdi-arrow-left"
        :to="'/'"
        >Home</v-btn
      >
      <div class="mt-5">
        <div style="min-width: 300px; max-width: 500px" class="d-flex ma-auto">
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
            :disabled="selectedSubject ? false : true"
            v-model="numberOfItems"
            class="float-start"
            width="300px"
            density="default"
            label="Items"
            placeholder="(Min of 5 items)"
            :min="minimumItems"
            :max="questionLength"
            type="number"
          >
          </v-text-field>
        </div>
      </div>
    </div>

    <div class="rounded-xl ma-auto" style="height: 100%">
      <div v-if="isQuestions">
        <div v-if="testReady">
          <v-card
            rounded="false"
            style="max-width: 900px; border-radius: 50px"
            class="ma-auto mt-2"
            variant="tonal"
          >
            <div class="text-center pt-5">
              <div>
                <v-chip>
                  {{ selectedSubject }}
                </v-chip>
              </div>
              <br />
              <v-chip style="padding: 25px">
                <v-icon size="50">mdi mdi-cat</v-icon>
                <h1 style="font-size: 32pt">
                  {{ cardIndex + 1 }}
                </h1>
              </v-chip>
            </div>
            <div class="pa-10 text-center">
              <div style="font-size: 18pt" id="question">
                {{ questions[cardIndex].question }}
              </div>
              <br />
              <!-- CHOICES -->
              <div
                v-if="questions[cardIndex].choices[0]"
                class="mt-10"
                id="choices"
              >
                <v-row gutters>
                  <v-col
                    v-for="(label, index) in labels.slice(0, max)"
                    :key="index"
                    cols="12"
                    sm="6"
                  >
                    <div class="mx-2">
                      <v-card
                        v-if="questions[cardIndex].choices[0][label]"
                        :color="
                          changeColor && correctLabel == label
                            ? 'green'
                            : changeColor && wrongLabel == label
                            ? 'red'
                            : ''
                        "
                        @click="
                          !isDisabled
                            ? checkAnswer(
                                label,
                                questions[cardIndex],
                                questions[cardIndex].choices[0][label]
                              )
                            : ''
                        "
                      >
                        <div class="pa-5">
                          <v-chip class="bg-white float-start mb-5"
                            ><b>{{ label }}</b></v-chip
                          >
                          <div class="mt-1">
                            {{ questions[cardIndex].choices[0][label] }}
                          </div>
                        </div>
                      </v-card>
                    </div>
                  </v-col>
                </v-row>
              </div>

              <!-- IDENTIFY -->
              <div v-else>
                <div class="mt-8 ma-auto" style="width: 75%">
                  <v-text-field v-model="stringAnswer"></v-text-field>
                  <div v-if="openOverlay" class="text-green">
                    <h4>
                      Correct Answer: {{ questions[cardIndex].correct_answer }}
                    </h4>
                  </div>
                  <br />

                  <v-btn
                    v-if="submitButton"
                    @click="
                      checkAnswer(null, questions[cardIndex], stringAnswer)
                    "
                    >SUBMIT</v-btn
                  >
                </div>
              </div>
            </div>
            <br />
          </v-card>

          <div v-if="openRetake">
            <v-card-actions class="mt-5 justify-center">
              <v-btn
                variant="tonal"
                color="white"
                to="/"
                icon="mdi-home"
              ></v-btn>
              <v-spacer></v-spacer>
              <v-btn
                append-icon="mdi mdi-database"
                variant="tonal"
                color="white"
                @click="openResult = true"
                >View Scores</v-btn
              >
              <v-btn
                append-icon="mdi mdi-refresh-circle"
                variant="tonal"
                color="white"
                @click="retake"
                >RETAKE</v-btn
              >

              <v-btn
                append-icon="mdi mdi-open-in-new"
                variant="tonal"
                color="white"
                @click="reloadPage()"
                >NEW TEST</v-btn
              >
            </v-card-actions>
          </div>
        </div>

        <div
          v-else
          style="
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
          "
        >
          <v-btn
            rounded="false"
            min-width="100px"
            width="300px"
            height="100px"
            @click="
              numberOfItems <= questionLength ? (testReady = true) : notify()
            "
            style="border-radius: 30px"
          >
            <h2>
              <v-icon>mdi mdi-pen</v-icon>
              START TEST
            </h2>
          </v-btn>
        </div>
      </div>
    </div>

    <div class="text-center">
      <v-btn
        height="40px"
        width="500px"
        append-icon="mdi mdi-arrow-right-drop-circle"
        v-if="openOverlay"
        @click="nextQuestion"
        class="mt-10"
        :color="'blue'"
      >
        {{ cardIndex < numberOfItems - 1 ? "NEXT" : "FINISH" }}
      </v-btn>
    </div>

    <v-dialog v-model="openResult" width="500px">
      <v-card style="border-radius: 50px" :rounded="'false'">
        <div class="pa-10 text-center">
          <h1>Test Results</h1>
          <br />

          <v-card class="ma-auto" width="150px" variant="tonal">
            <div class="py-4 px-1">
              Rating
              <v-chip> {{ score }}% </v-chip>
            </div>
          </v-card>
          <div class="pa-5">Your Score</div>
          <v-progress-circular
            :model-value="score"
            :size="150"
            :width="30"
            :color="score >= 75.0 ? 'green' : 'red'"
          >
            <h2>{{ scoreTracker }}/{{ numberOfItems }}</h2>
          </v-progress-circular>

          <div class="mt-2"></div>
          <div style="font-style: italic" class="pa-5">
            <p>
              {{
                passedTest
                  ? "Congratulations!"
                  : quotes[Math.floor(Math.random() * quotes.length)]
              }}
            </p>
          </div>
          <v-card-actions class="mt-5 justify-center">
            <v-btn variant="tonal" color="white" to="/" icon="mdi-home"></v-btn>
            <v-spacer></v-spacer>
            <v-btn
              append-icon="mdi mdi-refresh-circle"
              variant="tonal"
              color="white"
              @click="retake"
              >RETAKE</v-btn
            >
            <v-btn
              append-icon="mdi mdi-open-in-new"
              variant="tonal"
              color="white"
              @click="reloadPage()"
              >NEW TEST</v-btn
            >
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
    <v-overlay
      :model-value="openOverlay"
      class="align-center justify-center"
      :scrim="isCorrect ? 'green' : 'red'"
    >
      <v-card
        v-if="openOverlay"
        style="border-radius: 50px"
        :rounded="'false'"
        width="400px"
      >
        <div class="pa-16">
          <div class="text-center">
            <h1>{{ isCorrect ? "Correct!" : "Incorrect!" }}</h1>
          </div>
          <br />

          <div class="text-center">
            <v-icon size="100" :color="isCorrect ? 'green' : 'red'"
              >{{ isCorrect ? "mdi mdi-check-circle" : "mdi mdi-close-circle" }}
            </v-icon>
            <br />
            <v-btn
              @click="nextQuestion"
              class="mt-10"
              :color="isCorrect ? 'green' : 'red'"
            >
              {{ cardIndex < numberOfItems - 1 ? "NEXT QUESTION" : "FINISH" }}
            </v-btn>
          </div>
        </div>
      </v-card>
    </v-overlay>
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
      quotes: [
        "Success is not final, failure is not fatal: it is the courage to continue that counts.",
        "Failure is simply the opportunity to begin again, this time more intelligently.",
        "Your setbacks are a setup for a comeback.",
        "The only real mistake is the one from which we learn nothing.",
        "I have not failed. I've just found 10,000 ways that won't work.",
        "Don't let a bad score define your intelligence. A test is a small snapshot; it doesn't capture your full potential.",
        "Our greatest glory is not in never falling, but in rising every time we fall.",
        "Remember that failure is an event, not a person.",
        "Sometimes you win, sometimes you learn.",
        "The best way to predict your future is to create it.",
      ],
      scoreTracker: 0,
      passedTest: false,
      openResult: false,
      openRetake: false,
      stringAnswer: null,
      raw_questions: null,
      openOverlay: false,
      isCorrect: false,
      isQuestions: false,
      isDisabled: false,
      wrongLabel: null,
      correctLabel: null,
      changeColor: false,
      numberOfItems: null,
      testReady: false,
      cardIndex: 0,
      minimumItems: 3,
      max: 6,
      labels: ["A", "B", "C", "D", "E", "F"], // Define your labels here

      submitButton: true,
      questionLength: null,
      questions: null,
      selectedSubject: null,
      subjects: [],
    };
  },

  watch: {
    selectedSubject(subject) {
      this.questions = null;
      this.testReady = false;
      this.numberOfItems = null;
      this.getSubjectQuestions(subject);
      console.log("HEYYYYY", this.questions);
    },
    numberOfItems(newValue) {
      if (newValue != null) {
        this.processQuestions(this.raw_questions, newValue);
        this.resetFields();
      } else {
        this.isQuestions = false;
      }
      console.log("HEYYYYY 22 ", this.questions);
    },
  },
  computed: {
    score() {
      return ((this.scoreTracker / this.numberOfItems) * 100).toFixed(0);
    },
  },

  mounted() {
    this.getAllSubjects();
    this.scoreTracker = 0;
  },
  methods: {
    reloadPage() {
      this.scoreTracker = 0;
      window.location.reload();
    },
    notify() {
      alert("Number of items exceeds actual number of questions!");
    },
    nextQuestion() {
      let limit = this.numberOfItems - 1;
      console.log("INDEXX", this.cardIndex);
      console.log("LIMIT", limit);

      if (this.cardIndex < limit) {
        console.log("INDEXX", this.cardIndex);
        console.log("LIMIT", limit);
        this.resetFields();
        this.cardIndex++;
      } else {
        // FINISHED
        this.openOverlay = false;
        this.openResult = true;
        this.openRetake = true;
        this.passedTest = this.score >= 75 ? true : false;
        this.submitButton = false;
      }
    },

    retake() {
      this.scoreTracker = 0;
      this.submitButton = true;
      this.openRetake = false;
      this.openResult = false;
      this.resetFields();
      this.cardIndex = 0;
      this.questions = this.randomize(this.questions);
    },

    preprocess(input) {
      if (input != null) {
        return input
          .toLowerCase() // Convert to lowercase
          .replace(/\s+/g, "") // Remove all whitespace
          .replace(/[^a-z0-9]/g, ""); // Remove special characters and punctuation
      } else {
        return input;
      }
    },

    randomize(input) {
      return input.sort(() => Math.random() - 0.5);
    },

    resetFields() {
      this.changeColor = false;
      this.openOverlay = false;
      this.isDisabled = false;
      this.wrongLabel = null;
      this.correctLabel = null;
      this.isCorrect = false;
      this.stringAnswer = null;
      this.submitButton = true;
    },
    checkAnswer(label = null, question, answer) {
      this.isDisabled = true;
      let is_correct =
        this.preprocess(answer) == this.preprocess(question.correct_answer);
      this.isCorrect = is_correct;
      this.changeColor = true;
      console.log(question.correct_answer);
      if (label != null) {
        if (is_correct) {
          this.correctLabel = label;
          this.scoreTracker++;
        } else {
          this.wrongLabel = label;
          for (const [key, value] of Object.entries(question.choices[0])) {
            if (value === question.correct_answer) {
              this.correctLabel = key;
              break;
            }
          }
        }
      } else {
        if (is_correct) {
          this.scoreTracker++;
        }
      }

      this.openOverlay = true;
      this.submitButton = false;
    },

    processQuestions(raw_questions, items) {
      this.questions = raw_questions.slice(0, items);
      this.isQuestions = true;
      this.cardIndex = 0;
    },

    async getSubjectQuestions(subject) {
      let response = await getQuestions(subject);
      let randomized = response.data;
      randomized = this.randomize(randomized);
      this.raw_questions = randomized;
      this.questionLength = this.raw_questions.length;
    },
    async getAllSubjects() {
      let response = await getSubjects();
      console.log(response);

      this.subjects = response.data;
    },
  },
};
</script>
