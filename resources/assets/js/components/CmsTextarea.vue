<template>
    <textarea v-if="type === 'textarea'" v-bind:name="dataName" cols="30" rows="4">{{dataValue}}</textarea>
    <textarea v-else-if="type === 'textarea_large'" v-bind:name="dataName" cols="30" rows="16">{{dataValue}}</textarea>
    <input v-else-if="type === 'text'" type="text" v-bind:name="dataName" v-bind:value="dataValue" />
</template>

<script>
    function selectType(config, name) {
        var type = 'text';

        _.forEach(config, function (value, key){
            var pattern = new RegExp(key);
            if (pattern.test(name)) {
                type = value;
                return false;
            }
        });

        return type;
    }

    export default {
        props: ['dataConfig', 'dataName', 'dataValue'],
        data() {
            var config = this.dataConfig || {},
                type = selectType(config, this.dataName);

            return {
                type: type
            };
        }


    };

</script>
