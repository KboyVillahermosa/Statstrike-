<template>
  <Head title="Workout Templates" />

  <DashboardLayout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Create Your Own Card -->
      <div class="bg-gray-900 rounded-lg border-2 border-orange-500 p-6 flex flex-col items-center text-center">
        <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mb-4">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        <h2 class="text-xl font-bold text-white mb-2">Create Your Own</h2>
        <p class="text-gray-400 mb-6 text-sm">
          Build a workout tailored to your specific goals and needs.
        </p>
        <button @click="openBuilder()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
          Start Building
        </button>
      </div>

      <!-- Templates -->
      <div v-for="t in templates" :key="t.id" class="bg-gray-900 rounded-lg border border-gray-800 p-6 flex flex-col">
        <div class="flex items-start justify-between gap-3">
          <div>
            <h2 class="text-xl font-bold text-white mb-2">{{ t.title }}</h2>
            <p class="text-gray-400 mb-4 text-sm">{{ t.description }}</p>
          </div>
          <button @click="removeTemplate(t.id)" class="text-red-400 hover:text-red-300 text-sm">Delete</button>
        </div>
        <ul class="space-y-2 mb-4 flex-1">
          <li class="flex items-start gap-2 text-gray-300 text-sm" v-for="(item, idx) in t.items" :key="idx">
            <span class="text-orange-500 mt-0.5">✓</span>
            <span>{{ item }}</span>
          </li>
        </ul>
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full" v-for="(tag, idx) in t.tags" :key="idx">{{ tag }}</span>
        </div>
        <div class="text-xs text-gray-400 mb-3">Rounds: {{ t.rounds }} • Intensity: {{ t.intensity }} • Rest: {{ t.rest }}m</div>
        <button @click="openRunner(t)" class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
          Start Workout
        </button>
      </div>
    </div>

    <!-- Run Modal -->
    <div v-if="runner.open" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/70" @click="closeRunner"></div>
      <div class="relative bg-gray-900 border border-gray-800 rounded-xl p-6 w-full max-w-xl mx-auto">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-2xl font-bold text-white">{{ runner.template.title }}</h3>
            <p class="text-xs text-gray-400">Rounds: {{ runner.template.rounds }} • Intensity: {{ runner.template.intensity }} • Rest: {{ runner.template.rest }}m</p>
          </div>
          <button class="text-gray-400 hover:text-white" @click="closeRunner">✕</button>
        </div>
        <div class="text-center">
          <div class="text-6xl font-bold tabular-nums" :class="session.phase === 'work' ? 'text-orange-400' : 'text-blue-400'">{{ mmss }}</div>
          <div class="mt-1 text-sm text-gray-400">Round {{ session.round }} / {{ runner.template.rounds }} — <span :class="session.phase === 'work' ? 'text-orange-400' : 'text-blue-400'">{{ phaseLabel }}</span></div>
          <div class="mt-4 flex justify-center gap-3">
            <button @click="togglePause" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700">{{ session.paused ? 'Resume' : 'Pause' }}</button>
            <button @click="skipPhase" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700">Skip</button>
            <button @click="closeRunner" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700">Stop</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Builder Modal -->
    <div v-if="builder.open" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/70" @click="closeBuilder"></div>
      <div class="relative bg-gray-900 border border-gray-800 rounded-xl p-6 w-full max-w-xl mx-auto">
        <div class="flex items-start justify-between mb-4">
          <h3 class="text-2xl font-bold text-white">Create Workout Template</h3>
          <button class="text-gray-400 hover:text-white" @click="closeBuilder">✕</button>
        </div>
        <div class="grid grid-cols-1 gap-4">
          <div>
            <label class="block text-sm text-gray-300 mb-1">Title</label>
            <input v-model="builder.form.title" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2" placeholder="e.g. Boxing Essentials" />
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-1">Description</label>
            <textarea v-model="builder.form.description" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2" rows="2" placeholder="Short description"></textarea>
          </div>
          <div class="grid grid-cols-3 gap-3">
            <div>
              <label class="block text-sm text-gray-300 mb-1">Rounds</label>
              <input type="number" min="1" max="20" v-model.number="builder.form.rounds" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2" />
            </div>
            <div>
              <label class="block text-sm text-gray-300 mb-1">Intensity</label>
              <select v-model="builder.form.intensity" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2">
                <option value="easy">Easy (3m)</option>
                <option value="medium">Medium (5m)</option>
                <option value="hard">Hard (8m)</option>
              </select>
            </div>
            <div>
              <label class="block text-sm text-gray-300 mb-1">Rest (minutes)</label>
              <input type="number" min="0" max="10" v-model.number="builder.form.rest" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2" />
            </div>
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-1">Tags (comma separated)</label>
            <input v-model="builder.form.tags" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2" placeholder="e.g. Boxing, Cardio" />
          </div>
          <div>
            <label class="block text-sm text-gray-300 mb-1">Items (one per line)</label>
            <textarea v-model="builder.form.itemsRaw" class="w-full bg-gray-800 text-white rounded-lg px-3 py-2" rows="4" placeholder="List of steps"></textarea>
          </div>
        </div>
        <div class="mt-5 flex justify-end gap-3">
          <button @click="closeBuilder" class="px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-700">Cancel</button>
          <button @click="saveTemplate" class="px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-500">Save Template</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

// Templates list (local)
function cryptoRandomId() {
  if (typeof crypto !== 'undefined' && crypto.getRandomValues) {
    const arr = new Uint32Array(2);
    crypto.getRandomValues(arr);
    return Array.from(arr).map(n => n.toString(36)).join('');
  }
  return Math.random().toString(36).slice(2);
}

const templates = ref([
  { id: cryptoRandomId(), title: 'Boxing Essentials', description: 'Master the fundamentals with this classic boxing workout.', rounds: 5, intensity: 'medium', rest: 2, tags: ['Boxing','Beginner','Cardio'], items: ['10 min jump rope','5 rounds shadow boxing','5 rounds heavy bag','3 rounds speed bag'] },
  { id: cryptoRandomId(), title: 'Muay Thai Power', description: 'Develop explosive power in your kicks, knees, and elbows.', rounds: 5, intensity: 'medium', rest: 2, tags: ['Muay Thai','Intermediate','Power'], items: ['Clinch work and drills','5 rounds of pad work','Heavy bag conditioning','Technique sparring'] },
  { id: cryptoRandomId(), title: 'BJJ Drilling', description: 'Sharpen your ground game with focused solo and partner drills.', rounds: 4, intensity: 'easy', rest: 2, tags: ['BJJ','All Levels','Technique'], items: ['Movement drills','Submission chains','Guard passing series','Positional sparring'] },
]);

// Runner modal state and session
const runner = ref({ open: false, template: null });
const session = ref({ round: 1, phase: 'work', remaining: 0, paused: false, timerId: null });

const phaseLabel = computed(() => session.value.phase === 'work' ? 'Work' : 'Rest');
const mmss = computed(() => {
  const s = Math.max(0, session.value.remaining | 0);
  const m = Math.floor(s / 60).toString().padStart(2, '0');
  const r = (s % 60).toString().padStart(2, '0');
  return `${m}:${r}`;
});

const intensityToSeconds = (intensity) => {
  switch (intensity) {
    case 'hard': return 8 * 60;
    case 'medium': return 5 * 60;
    default: return 3 * 60;
  }
};

const clearTimer = () => {
  if (session.value.timerId) {
    clearInterval(session.value.timerId);
    session.value.timerId = null;
  }
};

const tick = () => {
  if (session.value.paused) return;
  session.value.remaining -= 1;
  if (session.value.remaining <= 0) {
    if (session.value.phase === 'work') {
      session.value.phase = 'rest';
      session.value.remaining = (runner.value.template?.rest || 0) * 60;
    } else {
      if (session.value.round >= (runner.value.template?.rounds || 1)) {
        closeRunner();
        return;
      }
      session.value.round += 1;
      session.value.phase = 'work';
      session.value.remaining = intensityToSeconds(runner.value.template?.intensity || 'easy');
    }
  }
};

const startTimer = () => {
  clearTimer();
  session.value.timerId = setInterval(tick, 1000);
};

function openRunner(tpl) {
  runner.value.open = true;
  runner.value.template = tpl;
  session.value.round = 1;
  session.value.phase = 'work';
  session.value.paused = false;
  session.value.remaining = intensityToSeconds(tpl.intensity);
  startTimer();
}
function closeRunner() {
  clearTimer();
  runner.value.open = false;
  runner.value.template = null;
}
function togglePause() { session.value.paused = !session.value.paused; }
function skipPhase() {
  if (!runner.value.template) return;
  if (session.value.phase === 'work') {
    session.value.phase = 'rest';
    session.value.remaining = (runner.value.template.rest || 0) * 60;
  } else {
    if (session.value.round >= runner.value.template.rounds) return closeRunner();
    session.value.round += 1;
    session.value.phase = 'work';
    session.value.remaining = intensityToSeconds(runner.value.template.intensity);
  }
}

// Builder modal state
const builder = ref({
  open: false,
  form: { title: '', description: '', rounds: 5, intensity: 'easy', rest: 2, tags: '', itemsRaw: '' }
});

function openBuilder() { builder.value.open = true; }
function closeBuilder() {
  builder.value.open = false;
  builder.value.form = { title: '', description: '', rounds: 5, intensity: 'easy', rest: 2, tags: '', itemsRaw: '' };
}
function saveTemplate() {
  const t = builder.value.form;
  if (!t.title) return;
  const newTpl = {
    id: cryptoRandomId(),
    title: t.title,
    description: t.description,
    rounds: Math.max(1, Math.min(20, t.rounds || 1)),
    intensity: t.intensity,
    rest: Math.max(0, Math.min(10, t.rest || 0)),
    tags: (t.tags || '').split(',').map(s => s.trim()).filter(Boolean),
    items: (t.itemsRaw || '').split('\n').map(s => s.trim()).filter(Boolean),
  };
  templates.value.unshift(newTpl);
  closeBuilder();
}
function removeTemplate(id) {
  templates.value = templates.value.filter(t => t.id !== id);
}
</script>

