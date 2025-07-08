<template>
  <div class="space-y-2 bg-gray-800 p-4 rounded text-white">
    <div class="flex items-center gap-2 font-semibold">
      <span v-if="isWinner(game.team_one.id)">🏆</span>
      <span><i>{{ game.team_one.player_one }}</i> / <i>{{ game.team_one.player_two }}</i></span>
    </div>

    <div v-for="(set, index) in sets" :key="index" class="text-sm flex items-center justify-between">
      <span>Set {{ index + 1 }}:</span>
      <span class="flex items-center gap-2">
        <span :class="set.winner === 'team_one' ? 'text-green-300 font-bold' : 'text-red-300'">
          {{ set.teamOne }}
        </span>
        X
        <span :class="set.winner === 'team_two' ? 'text-green-300 font-bold' : 'text-red-300'">
          {{ set.teamTwo }}
        </span>
        <span v-if="set.tie_break" class="text-xs text-yellow-300">(tie-break)</span>
      </span>
    </div>

    <div class="flex items-center gap-2 font-semibold">
      <span v-if="isWinner(game.team_two.id)">🏆</span>
      <span><i>{{ game.team_two.player_one }}</i> / <i>{{ game.team_two.player_two }}</i></span>
    </div>

    <div class="text-sm text-yellow-400 mt-2 font-medium" v-if="winnerName">
      Vitória de: {{ winnerName }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  game: Object,
});

const sets = computed(() => {
  const results = props.game.set_results ?? [];

  return [0, 1, 2].map((index) => {
    const set = results[index];
    if (!set) {
      return { teamOne: 0, teamTwo: 0, tie_break: false, winner: null };
    }

    const isTeamOneWin = set.team_win === props.game.team_one.id;

    return {
      teamOne: isTeamOneWin ? set.game_win : set.game_lose,
      teamTwo: isTeamOneWin ? set.game_lose : set.game_win,
      tie_break: set.tie_break,
      winner: isTeamOneWin ? 'team_one' : 'team_two',
    };
  });
});

const isWinner = (teamId) => {
  const teamWinCount = sets.value.filter((s) => s.winner === 'team_one').length;
  const teamLoseCount = sets.value.filter((s) => s.winner === 'team_two').length;
  return (teamWinCount > teamLoseCount && props.game.team_one.id === teamId)
      || (teamLoseCount > teamWinCount && props.game.team_two.id === teamId);
};

const winnerName = computed(() => {
  const t1Wins = sets.value.filter((s) => s.winner === 'team_one').length;
  const t2Wins = sets.value.filter((s) => s.winner === 'team_two').length;

  if (t1Wins === t2Wins) return null;

  return t1Wins > t2Wins
    ? `${props.game.team_one.player_one} / ${props.game.team_one.player_two}`
    : `${props.game.team_two.player_one} / ${props.game.team_two.player_two}`;
});
</script>
