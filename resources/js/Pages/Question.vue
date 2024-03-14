<script setup>
    import Layout from '@/Shared/Layout.vue';
    import NewQuestionModal from '@/Shared/NewQuestionModal.vue';
    import { router } from '@inertiajs/vue3';
    import {ref} from 'vue'
    import {usePage} from '@inertiajs/vue3'
    import {computed} from 'vue'

    const page=usePage()
    const success=computed(()=>page.props.flash.success)
    const showNewQuestionModal=ref(false)
    const createdQuestion=ref(null)
    const newAnswers=ref([])
    const selectedAnswer=ref(null)
    let answerId=1

    function createQuestion(){
        showNewQuestionModal.value=true
    }

    function destroyModal(){
        showNewQuestionModal.value=false
    }

    function addNewAnswer() {
        const newAnswer={
            id: answerId++,
            answer:'',
            correct_answer:0
        }

        newAnswers.value.push(newAnswer)
    }

    function handleRadioToggle(Id) {
        selectedAnswer.value=Id
        newAnswers.value.forEach(answer=>{
            if(answer.id===Id){
                answer.correct_answer=1
            }else{
                answer.correct_answer=0
            }
        })
    }

    function validateAnswers() {
        for(const answer of newAnswers.value){
            if (answer.answer.trim()==='') {
                return false
            }
        }
        return true
    }

    function answerCount() {
        if (newAnswers.length<4) {
            alert('Four answers required submit')
        }else if(newAnswers.length===4){
            return true
        }
        return false
    }
    
    function submitQuestion() {
        if (!createdQuestion.value) {
            alert('question cannot be empty')
            return false
        }

        if (!validateAnswers() && !answerCount()) {
            alert('Fill all inputs before submitting')
            return false
        }

        router.post('/questions', {
            question:createdQuestion.value,
            answers:newAnswers.value
        })

        router.on('success', ()=>{
            createdQuestion.value = null,
            newAnswers.value=[]
        })
    }

    const props = defineProps({
        questions: Object,
        errors: Object,
    })

</script>

<template>
    <Layout>
        <button @click="createQuestion" class="btn btn-success">Create</button>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Question</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(question, index) in questions">
                    <th scope="row">{{ index+1 }}</th>
                    <td>{{ question.question }}</td>
                    <td>
                        <button @click="viewQuestion(index)" class="btn btn-primary">View</button>
                        <button class="btn btn-success">Edit</button>
                        <button class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <Teleport to="body">
            <NewQuestionModal :show="showNewQuestionModal" @close="destroyModal">
                <template #header>
                    <h5>Add New Question</h5>
                </template>
                <template #success>
                    <div  v-if="success" class="alert alert-success">{{ success }}</div>
                </template>
                <template #body>
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Question</label>
                            <input type="text" v-model="createdQuestion" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Answers</th>
                                <th scope="col">Correct ?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(answer, index) in newAnswers">
                                <th scope="row">{{ answer.id }}</th>
                                <td>
                                    <input type="text" v-model="answer.answer" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </td>
                                <td>
                                    <input :checked="answer.correct_answer === 1" class="form-check-input" :value="answer.id" @change="handleRadioToggle(answer.id)" type="radio">
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </template>
                <template #footer>
                    <span @click="addNewAnswer" v-if="newAnswers.length<4" ><h3>+</h3></span>
                    <button @click="destroyModal" class="btn btn-danger">Close</button>
                    <button v-if="newAnswers.length>3" @click="submitQuestion" class="btn btn-success">Submit</button>
                </template>
            </NewQuestionModal>
        </Teleport>
    </Layout>
</template>