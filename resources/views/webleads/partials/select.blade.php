<div id="app">

  <h2>Select Date</h2>
  
  <select v-model="selectedOption" @change="loadData">
    <option v-for="option in options">{{ option }}</option>
  </select>

  <div v-if="selectedOption && !items.length"><i>Loading</i></div>
  <select v-if="items.length">
    <option v-for="item in items">{{ item.label }}</option>
  </select>
  
</div>

<script>
	const app = new Vue({
  el:'#app',
  data:{
    options:["films","people","starships","vehicles","species","planets"],
    items:null,
    selectedOption:''
  },
  methods:{
    loadData:function() {
      this.items = null;
      let key = 'name';
      if(this.selectedOption === 'films') key = 'title';
      
      fetch('https://swapi.co/api/'+this.selectedOption)
      .then(res=>res.json())
      .then(res => {
        // "fix" the data to set a label for all types
        this.items = res.results.map((item) =>{
              item.label = item[key];
              return item;
        });
       
      });
    }
  }
});
</script>