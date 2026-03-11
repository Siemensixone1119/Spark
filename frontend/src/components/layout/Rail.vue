<template>
  <div class="rail-list">
    <ul class="rail-list--top">
      <li class="rail-list__item"></li>
      <li class="rail-list__item"></li>
      <li class="rail-list__item"></li>
      <li class="rail-list__item"></li>
    </ul>

    <ul class="rail-list--bottom">
      <li class="rail-list__item"></li>
      <li class="rail-list__item"></li>
    </ul>
  </div>

</template>

<style lang="scss" scoped>

.rail-list{
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  
}

.rail-list--top,
.rail-list--bottom {
  display: flex;
  flex-direction: column;
  gap: var(--space-8);
}


.rail-list__item {
  width: 30px;
  height: 30px;
  background-color: antiquewhite;
}
</style>