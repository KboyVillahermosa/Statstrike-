<template>
  <Head title="Workout Routine" />

  <DashboardLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6 mb-2">
        <div class="flex-1 min-w-0">
          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4 leading-tight">Weekly Workout Routines</h1>
          <p class="text-gray-400 text-base sm:text-lg leading-relaxed max-w-2xl">Create and manage your weekly workout schedule with built-in rest days for optimal recovery</p>
        </div>
        <button 
          @click="openBuilder()" 
          class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 sm:py-4 px-6 sm:px-8 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 transform hover:scale-[1.02] whitespace-nowrap flex-shrink-0"
        >
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <span class="text-sm sm:text-base">Create Routine</span>
        </button>
      </div>

      <!-- Routines List -->
      <div v-if="routines && routines.length > 0" class="space-y-8 sm:space-y-10 lg:space-y-12">
        <div 
          v-for="routine in routines" 
          :key="routine.id" 
          class="bg-gradient-to-br from-gray-950 to-gray-900 border border-gray-800 rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl hover:shadow-3xl transition-shadow duration-300"
        >
          <!-- Routine Header -->
          <div class="p-6 sm:p-8 lg:p-10 border-b border-gray-800 bg-gradient-to-r from-gray-900/50 to-gray-950/50">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-6">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4 flex-wrap">
                  <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white break-words">{{ routine.name }}</h2>
                  <span 
                    v-if="routine.is_active" 
                    class="px-3 sm:px-4 py-1.5 sm:py-2 bg-green-500/20 border border-green-500/30 text-green-400 text-xs sm:text-sm font-semibold rounded-full whitespace-nowrap"
                  >
                    ✓ Active
                  </span>
                  <span 
                    v-else 
                    class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gray-500/20 border border-gray-500/30 text-gray-400 text-xs sm:text-sm font-semibold rounded-full whitespace-nowrap"
                  >
                    Inactive
                  </span>
                </div>
                <p v-if="routine.description" class="text-gray-400 text-sm sm:text-base leading-relaxed max-w-3xl">{{ routine.description }}</p>
              </div>
              <div class="flex gap-2 sm:gap-3 flex-shrink-0">
                <button 
                  @click="editRoutine(routine)" 
                  class="px-4 sm:px-5 py-2.5 sm:py-3 bg-gray-800 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg"
                >
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  <span class="hidden sm:inline">Edit</span>
                </button>
                <button 
                  @click="deleteRoutine(routine.id)" 
                  class="px-4 sm:px-5 py-2.5 sm:py-3 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg"
                >
                  <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  <span class="hidden sm:inline">Delete</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Weekly Schedule -->
          <div class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 sm:gap-5 lg:gap-6">
              <div 
                v-for="day in daysOfWeek" 
                :key="day.value"
                :class="[
                  'rounded-xl border-2 transition-all duration-300 hover:scale-[1.02]',
                  getDayWorkout(routine, day.value) 
                    ? 'bg-gradient-to-br from-gray-900 via-gray-900 to-gray-950 border-orange-500/40 hover:border-orange-500/60 shadow-xl shadow-orange-500/10' 
                    : 'bg-gradient-to-br from-gray-800/40 via-gray-800/30 to-gray-900/40 border-gray-700/40 hover:border-gray-600/60 shadow-lg'
                ]"
              >
                <!-- Day Header -->
                <div class="p-4 sm:p-5 border-b border-gray-800/50">
                  <div class="flex items-center justify-between">
                    <div class="font-bold text-white text-base sm:text-lg uppercase tracking-wider">
                      {{ day.label }}
                    </div>
                    <div 
                      v-if="getDayWorkout(routine, day.value)"
                      class="w-3 h-3 bg-orange-500 rounded-full shadow-lg shadow-orange-500/50 animate-pulse"
                    ></div>
                    <div 
                      v-else
                      class="w-3 h-3 bg-blue-500 rounded-full shadow-lg shadow-blue-500/50"
                    ></div>
                  </div>
                </div>

                <!-- Workout Day Content -->
                <div v-if="getDayWorkout(routine, day.value)" class="p-4 sm:p-5 space-y-4">
                  <div>
                    <div class="text-white font-bold text-base sm:text-lg mb-2 leading-tight">
                      {{ getDayWorkout(routine, day.value).title }}
                    </div>
                    <div v-if="getDayWorkout(routine, day.value).description" class="text-gray-400 text-sm leading-relaxed">
                      {{ getDayWorkout(routine, day.value).description }}
                    </div>
                  </div>
                  
                  <div class="space-y-3">
                    <div class="text-xs sm:text-sm font-semibold text-gray-400 uppercase tracking-wider">Exercises</div>
                    <ul class="space-y-2 max-h-48 sm:max-h-56 overflow-y-auto custom-scrollbar">
                      <li 
                        v-for="(exercise, idx) in getDayWorkout(routine, day.value).exercises" 
                        :key="idx"
                        class="text-sm text-gray-300 flex items-start gap-2.5 leading-relaxed"
                      >
                        <span class="text-orange-500 mt-1 flex-shrink-0 font-bold">▸</span>
                        <span class="flex-1">{{ exercise }}</span>
                      </li>
                    </ul>
                  </div>
                  
                  <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-800/50">
                    <span class="px-3 py-1.5 bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs sm:text-sm font-semibold rounded-lg">
                      {{ getDayWorkout(routine, day.value).rounds }} rounds
                    </span>
                    <span 
                      class="px-3 py-1.5 text-xs sm:text-sm font-semibold rounded-lg border"
                      :class="{
                        'bg-green-500/20 border-green-500/30 text-green-400': getDayWorkout(routine, day.value).intensity === 'easy',
                        'bg-yellow-500/20 border-yellow-500/30 text-yellow-400': getDayWorkout(routine, day.value).intensity === 'medium',
                        'bg-red-500/20 border-red-500/30 text-red-400': getDayWorkout(routine, day.value).intensity === 'hard'
                      }"
                    >
                      {{ getDayWorkout(routine, day.value).intensity }}
                    </span>
                    <span class="px-3 py-1.5 bg-gray-800/50 border border-gray-700/50 text-gray-400 text-xs sm:text-sm font-semibold rounded-lg">
                      {{ getDayWorkout(routine, day.value).rest_minutes }}m rest
                    </span>
                  </div>
                  
                  <button 
                    @click="startWorkout(getDayWorkout(routine, day.value))"
                    class="w-full mt-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-sm font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 transform hover:scale-[1.02]"
                  >
                    Start Workout
                  </button>
                </div>

                <!-- Rest Day Content -->
                <div v-else class="p-6 sm:p-8 text-center">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                  </div>
                  <div class="text-blue-400 font-bold text-base sm:text-lg mb-2">Rest Day</div>
                  <div class="text-gray-500 text-sm">Recovery & Rest</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-gradient-to-br from-gray-950 to-gray-900 border border-gray-800 rounded-xl sm:rounded-2xl p-12 sm:p-16 lg:p-20 text-center shadow-2xl">
        <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8">
          <svg class="w-12 h-12 sm:w-14 sm:h-14 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4">No Workout Routines</h3>
        <p class="text-gray-400 text-base sm:text-lg mb-8 sm:mb-10 max-w-md mx-auto">Create your first weekly workout routine to get started with your fitness journey.</p>
        <button 
          @click="openBuilder()" 
          class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-3 sm:py-4 px-8 sm:px-10 rounded-lg transition-all duration-200 shadow-lg shadow-orange-500/20 hover:shadow-xl hover:shadow-orange-500/30 transform hover:scale-[1.02] text-base sm:text-lg"
        >
          Create Routine
        </button>
      </div>
    </div>

    <!-- Builder Modal -->
    <div v-if="builder.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
      <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeBuilder"></div>
      <div class="relative bg-gradient-to-br from-gray-900 to-gray-950 border border-gray-800 rounded-xl p-6 w-full max-w-6xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex items-start justify-between mb-6 sticky top-0 bg-gray-900/95 backdrop-blur-sm pb-4 border-b border-gray-800 -mx-6 px-6">
          <h3 class="text-2xl font-bold text-white">
            {{ builder.editing ? 'Edit' : 'Create' }} Workout Routine
          </h3>
          <button class="text-gray-400 hover:text-white transition-colors" @click="closeBuilder">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveRoutine" class="space-y-6">
          <!-- Routine Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Routine Name *</label>
              <input 
                v-model="builder.form.name" 
                required
                class="w-full bg-gray-800 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all" 
                placeholder="e.g. Boxing Weekly Program"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Active Status</label>
              <select 
                v-model="builder.form.is_active"
                class="w-full bg-gray-800 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
              >
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Description (Optional)</label>
            <textarea 
              v-model="builder.form.description" 
              class="w-full bg-gray-800 text-white rounded-lg px-4 py-3 border border-gray-700 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all resize-none" 
              rows="2" 
              placeholder="Describe your workout routine"
            ></textarea>
          </div>

          <!-- Weekly Schedule -->
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <h4 class="text-lg font-semibold text-white">Weekly Schedule</h4>
              <p class="text-xs text-gray-400">Leave days empty for rest days</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4">
              <div 
                v-for="day in daysOfWeek" 
                :key="day.value"
                :class="[
                  'rounded-lg p-4 border-2 transition-all',
                  hasDayWorkout(day.value)
                    ? 'bg-gray-800 border-orange-500/30'
                    : 'bg-gray-800/50 border-gray-700/50 border-dashed'
                ]"
              >
                <div class="flex items-center justify-between mb-3">
                  <div class="font-bold text-white text-xs uppercase tracking-wide">{{ day.label }}</div>
                  <button
                    type="button"
                    @click="toggleDayWorkout(day.value)"
                    :class="[
                      'w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all',
                      hasDayWorkout(day.value)
                        ? 'bg-orange-500 border-orange-500'
                        : 'bg-transparent border-gray-600 hover:border-gray-500'
                    ]"
                  >
                    <svg v-if="hasDayWorkout(day.value)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                </div>
                
                <div v-if="hasDayWorkout(day.value)" class="space-y-3">
                  <input 
                    v-model="builder.form.days[day.value].title" 
                    required
                    class="w-full bg-gray-900 text-white text-sm rounded px-3 py-2 border border-gray-700 focus:border-orange-500 focus:outline-none" 
                    placeholder="Workout title"
                  />
                  <textarea 
                    v-model="builder.form.days[day.value].description" 
                    class="w-full bg-gray-900 text-white text-xs rounded px-3 py-2 border border-gray-700 focus:border-orange-500 focus:outline-none resize-none" 
                    rows="2" 
                    placeholder="Description (optional)"
                  ></textarea>
                  <div>
                    <label class="text-xs text-gray-400 mb-1 block">Exercises (one per line) *</label>
                    <textarea 
                      v-model="builder.form.days[day.value].exercisesRaw" 
                      required
                      class="w-full bg-gray-900 text-white text-xs rounded px-3 py-2 border border-gray-700 focus:border-orange-500 focus:outline-none resize-none" 
                      rows="4" 
                      placeholder="Exercise 1&#10;Exercise 2&#10;Exercise 3"
                    ></textarea>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="text-xs text-gray-400 mb-1 block">Rounds</label>
                      <input 
                        type="number" 
                        v-model.number="builder.form.days[day.value].rounds" 
                        min="1" 
                        max="20"
                        required
                        class="w-full bg-gray-900 text-white text-xs rounded px-3 py-2 border border-gray-700 focus:border-orange-500 focus:outline-none"
                      />
                    </div>
                    <div>
                      <label class="text-xs text-gray-400 mb-1 block">Rest (min)</label>
                      <input 
                        type="number" 
                        v-model.number="builder.form.days[day.value].rest_minutes" 
                        min="0" 
                        max="10"
                        required
                        class="w-full bg-gray-900 text-white text-xs rounded px-3 py-2 border border-gray-700 focus:border-orange-500 focus:outline-none"
                      />
                    </div>
                  </div>
                  <div>
                    <label class="text-xs text-gray-400 mb-1 block">Intensity</label>
                    <select 
                      v-model="builder.form.days[day.value].intensity" 
                      required
                      class="w-full bg-gray-900 text-white text-xs rounded px-3 py-2 border border-gray-700 focus:border-orange-500 focus:outline-none"
                    >
                      <option value="easy">Easy</option>
                      <option value="medium">Medium</option>
                      <option value="hard">Hard</option>
                    </select>
                  </div>
                </div>
                <div v-else class="text-center py-4">
                  <div class="text-gray-500 text-xs">Rest Day</div>
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-gray-800 sticky bottom-0 bg-gray-900/95 backdrop-blur-sm -mx-6 px-6 pb-0">
            <button 
              type="button"
              @click="closeBuilder" 
              class="px-6 py-3 rounded-lg bg-gray-800 hover:bg-gray-700 text-white font-semibold transition-colors"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-6 py-3 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold transition-all shadow-lg shadow-orange-500/20"
            >
              {{ builder.editing ? 'Update' : 'Create' }} Routine
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Workout Runner Modal -->
    <div v-if="runner.open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeRunner"></div>
      <div class="relative bg-gradient-to-br from-gray-900 to-gray-950 border border-gray-800 rounded-xl p-6 sm:p-8 w-full max-w-4xl mx-auto shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between mb-6">
          <div class="flex-1">
            <h3 class="text-2xl font-bold text-white">{{ runner.workout?.title }}</h3>
            <p class="text-xs text-gray-400 mt-1">Rounds: {{ runner.workout?.rounds }} • Intensity: {{ runner.workout?.intensity }} • Rest: {{ runner.workout?.rest_minutes }}m</p>
          </div>
          <button class="text-gray-400 hover:text-white transition-colors ml-4" @click="closeRunner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Timer Section -->
          <div class="text-center space-y-6">
            <div class="text-7xl font-bold tabular-nums mb-2" :class="session.phase === 'work' ? 'text-orange-400' : 'text-blue-400'">{{ mmss }}</div>
            <div class="text-sm text-gray-400">Round {{ session.round }} / {{ runner.workout?.rounds }} — <span :class="session.phase === 'work' ? 'text-orange-400 font-semibold' : 'text-blue-400 font-semibold'">{{ phaseLabel }}</span></div>
            <div class="mb-6">
              <div class="text-lg font-semibold text-white">{{ currentExerciseLabel }}</div>
            </div>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
              <button @click="togglePause" class="px-6 py-3 rounded-lg bg-gray-800 hover:bg-gray-700 text-white font-semibold transition-colors">
                {{ session.paused ? 'Resume' : 'Pause' }}
              </button>
              <button @click="skipPhase" class="px-6 py-3 rounded-lg bg-gray-800 hover:bg-gray-700 text-white font-semibold transition-colors">Skip</button>
              <button @click="closeRunner" class="px-6 py-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 font-semibold transition-colors">Stop</button>
            </div>
            <div class="pt-4 border-t border-gray-800">
              <button 
                @click="toggleCamera" 
                :class="[
                  'w-full px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center gap-2',
                  cameraOpen 
                    ? 'bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400' 
                    : 'bg-orange-500/20 hover:bg-orange-500/30 border border-orange-500/30 text-orange-400'
                ]"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="!cameraOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ cameraOpen ? 'Close Camera' : 'Open Motion Tracker' }}
              </button>
              <p v-if="cameraError" class="text-xs text-red-400 mt-2">{{ cameraError }}</p>
            </div>
          </div>

          <!-- Camera Section -->
          <div v-if="cameraOpen" class="space-y-4">
            <div class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden">
              <div class="p-3 border-b border-gray-800 bg-gray-800/50">
                <h4 class="text-sm font-semibold text-white flex items-center gap-2">
                  <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  Motion Tracker
                </h4>
              </div>
              <div class="relative bg-black aspect-video">
                <video 
                  ref="cameraVideo" 
                  autoplay 
                  playsinline 
                  muted
                  class="w-full h-full object-cover"
                ></video>
                <div v-if="!isCameraActive" class="absolute inset-0 flex items-center justify-center bg-gray-900">
                  <div class="text-center">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm text-gray-400">Camera will start when enabled</p>
                  </div>
                </div>
              </div>
              <div class="p-4 bg-gray-800/30">
                <p class="text-xs text-gray-400 text-center">
                  Track your form and movements in real-time during your workout
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount } from 'vue';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
  routines: {
    type: Array,
    default: () => [],
  },
});

const daysOfWeek = [
  { label: 'Monday', value: 'monday' },
  { label: 'Tuesday', value: 'tuesday' },
  { label: 'Wednesday', value: 'wednesday' },
  { label: 'Thursday', value: 'thursday' },
  { label: 'Friday', value: 'friday' },
  { label: 'Saturday', value: 'saturday' },
  { label: 'Sunday', value: 'sunday' },
];

const routines = ref(props.routines || []);

// Builder modal state
const builder = ref({
  open: false,
  editing: false,
  routineId: null,
  form: {
    name: '',
    description: '',
    is_active: true,
    days: daysOfWeek.reduce((acc, day) => {
      acc[day.value] = {
        title: '',
        description: '',
        exercisesRaw: '',
        rounds: 5,
        intensity: 'medium',
        rest_minutes: 2,
        enabled: false,
      };
      return acc;
    }, {}),
  },
});

function hasDayWorkout(dayValue) {
  return builder.value.form.days[dayValue].enabled;
}

function toggleDayWorkout(dayValue) {
  builder.value.form.days[dayValue].enabled = !builder.value.form.days[dayValue].enabled;
  if (!builder.value.form.days[dayValue].enabled) {
    // Reset form when disabling
    builder.value.form.days[dayValue] = {
      title: '',
      description: '',
      exercisesRaw: '',
      rounds: 5,
      intensity: 'medium',
      rest_minutes: 2,
      enabled: false,
    };
  }
}

function openBuilder() {
  builder.value.open = true;
  builder.value.editing = false;
  builder.value.routineId = null;
  resetBuilderForm();
}

function editRoutine(routine) {
  builder.value.open = true;
  builder.value.editing = true;
  builder.value.routineId = routine.id;
  builder.value.form.name = routine.name;
  builder.value.form.description = routine.description || '';
  builder.value.form.is_active = routine.is_active ?? true;
  
  // Reset days
  daysOfWeek.forEach(day => {
    const dayWorkout = routine.days?.find(d => d.day_of_week === day.value);
    if (dayWorkout) {
      builder.value.form.days[day.value] = {
        title: dayWorkout.title,
        description: dayWorkout.description || '',
        exercisesRaw: Array.isArray(dayWorkout.exercises) ? dayWorkout.exercises.join('\n') : '',
        rounds: dayWorkout.rounds,
        intensity: dayWorkout.intensity,
        rest_minutes: dayWorkout.rest_minutes,
        enabled: true,
      };
    } else {
      builder.value.form.days[day.value] = {
        title: '',
        description: '',
        exercisesRaw: '',
        rounds: 5,
        intensity: 'medium',
        rest_minutes: 2,
        enabled: false,
      };
    }
  });
}

function resetBuilderForm() {
  builder.value.form.name = '';
  builder.value.form.description = '';
  builder.value.form.is_active = true;
  daysOfWeek.forEach(day => {
    builder.value.form.days[day.value] = {
      title: '',
      description: '',
      exercisesRaw: '',
      rounds: 5,
      intensity: 'medium',
      rest_minutes: 2,
      enabled: false,
    };
  });
}

function closeBuilder() {
  builder.value.open = false;
  resetBuilderForm();
}

function saveRoutine() {
  const formData = {
    name: builder.value.form.name,
    description: builder.value.form.description,
    is_active: builder.value.form.is_active,
    days: daysOfWeek
      .filter(day => builder.value.form.days[day.value].enabled)
      .map(day => {
        const dayData = builder.value.form.days[day.value];
        const exercises = dayData.exercisesRaw
          .split('\n')
          .map(e => e.trim())
          .filter(e => e.length > 0);
        
        if (exercises.length === 0 || !dayData.title) {
          return null;
        }
        
        return {
          day_of_week: day.value,
          title: dayData.title,
          description: dayData.description || null,
          exercises: exercises,
          rounds: dayData.rounds,
          intensity: dayData.intensity,
          rest_minutes: dayData.rest_minutes,
          tags: [],
        };
      })
      .filter(Boolean),
  };

  if (formData.days.length === 0) {
    alert('Please add at least one workout day.');
    return;
  }

  if (builder.value.editing) {
    router.put(route('workouts.routines.update', builder.value.routineId), formData, {
      preserveScroll: true,
      onSuccess: () => {
        closeBuilder();
        router.reload();
      },
    });
  } else {
    router.post(route('workouts.routines.store'), formData, {
      preserveScroll: true,
      onSuccess: () => {
        closeBuilder();
        router.reload();
      },
    });
  }
}

function deleteRoutine(id) {
  if (confirm('Are you sure you want to delete this routine?')) {
    router.delete(route('workouts.routines.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        router.reload();
      },
    });
  }
}

function getDayWorkout(routine, dayOfWeek) {
  return routine.days?.find(d => d.day_of_week === dayOfWeek);
}

// Workout runner
const runner = ref({ open: false, workout: null });
const session = ref({ round: 1, itemIndex: 0, phase: 'work', remaining: 0, paused: false, timerId: null });

// Camera state
const cameraOpen = ref(false);
const cameraVideo = ref(null);
const isCameraActive = ref(false);
const cameraError = ref(null);
let cameraStream = null;

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

function itemDurationFor(workout) {
  const total = intensityToSeconds(workout?.intensity || 'easy');
  const count = Math.max(1, (workout?.exercises || []).length);
  return Math.max(30, Math.floor(total / count));
}

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
      session.value.remaining = (runner.value.workout?.rest_minutes || 0) * 60;
    } else {
      if (session.value.round >= (runner.value.workout?.rounds || 1)) {
        closeRunner();
        return;
      }
      session.value.round += 1;
      session.value.phase = 'work';
      session.value.itemIndex = 0;
      session.value.remaining = itemDurationFor(runner.value.workout);
    }
  }
};

const startTimer = () => {
  clearTimer();
  session.value.timerId = setInterval(tick, 1000);
};

function startWorkout(workout) {
  runner.value.open = true;
  runner.value.workout = workout;
  session.value.round = 1;
  session.value.itemIndex = 0;
  session.value.phase = 'work';
  session.value.paused = false;
  session.value.remaining = itemDurationFor(workout);
  startTimer();
}

function closeRunner() {
  clearTimer();
  closeCamera();
  runner.value.open = false;
  runner.value.workout = null;
}

// Camera functions
async function startCamera() {
  try {
    cameraError.value = null;
    
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      cameraError.value = 'Camera access is not supported in this browser.';
      return;
    }
    
    const hostname = window.location.hostname.toLowerCase();
    const isSecure = window.location.protocol === 'https:' || 
                    hostname === 'localhost' || 
                    hostname === '127.0.0.1' ||
                    hostname === '[::1]';
    if (!isSecure) {
      cameraError.value = 'Camera access requires HTTPS or localhost.';
      return;
    }
    
    cameraStream = await navigator.mediaDevices.getUserMedia({
      video: {
        facingMode: 'user',
        width: { ideal: 1280 },
        height: { ideal: 720 },
      },
    });
    
    if (cameraVideo.value) {
      cameraVideo.value.srcObject = cameraStream;
      await cameraVideo.value.play();
      isCameraActive.value = true;
    }
  } catch (error) {
    console.error('Camera error:', error);
    let errorMessage = 'Unable to access camera. ';
    
    if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
      errorMessage += 'Camera permission was denied. Please allow camera access.';
    } else if (error.name === 'NotFoundError') {
      errorMessage += 'No camera found.';
    } else if (error.name === 'NotReadableError') {
      errorMessage += 'Camera is already in use.';
    } else {
      errorMessage += error.message || 'Unknown error occurred.';
    }
    
    cameraError.value = errorMessage;
    isCameraActive.value = false;
    cameraOpen.value = false;
  }
}

function stopCamera() {
  if (cameraStream) {
    cameraStream.getTracks().forEach(track => track.stop());
    cameraStream = null;
  }
  if (cameraVideo.value) {
    cameraVideo.value.srcObject = null;
  }
  isCameraActive.value = false;
  cameraError.value = null;
}

function toggleCamera() {
  if (cameraOpen.value) {
    stopCamera();
    cameraOpen.value = false;
  } else {
    cameraOpen.value = true;
    startCamera();
  }
}

function closeCamera() {
  stopCamera();
  cameraOpen.value = false;
}

function togglePause() { 
  session.value.paused = !session.value.paused; 
}

function skipPhase() {
  if (!runner.value.workout) return;
  if (session.value.phase === 'work') {
    if (session.value.itemIndex < (runner.value.workout.exercises || []).length - 1) {
      session.value.itemIndex += 1;
      session.value.remaining = itemDurationFor(runner.value.workout);
    } else {
      if (session.value.round >= runner.value.workout.rounds) return closeRunner();
      session.value.phase = 'rest';
      session.value.remaining = (runner.value.workout.rest_minutes || 0) * 60;
    }
  } else {
    if (session.value.round >= runner.value.workout.rounds) return closeRunner();
    session.value.round += 1;
    session.value.itemIndex = 0;
    session.value.phase = 'work';
    session.value.remaining = itemDurationFor(runner.value.workout);
  }
}

const currentExerciseLabel = computed(() => {
  const workout = runner.value.workout;
  if (!workout) return '';
  const idx = session.value.itemIndex || 0;
  return workout.exercises?.[idx] || '';
});

onBeforeUnmount(() => {
  clearTimer();
  closeCamera();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(31, 41, 55, 0.5);
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(249, 115, 22, 0.5);
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(249, 115, 22, 0.7);
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .grid {
    grid-template-columns: 1fr;
  }
}

@media (min-width: 640px) and (max-width: 1024px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) and (max-width: 1280px) {
  .grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (min-width: 1280px) {
  .grid {
    grid-template-columns: repeat(7, 1fr);
  }
}
</style>
