<template>
    <div class="cms-viewer">
        <div class="title">
            <h1>{{ path }}</h1>

        </div>
        <div v-for="(val, key) in flatFields">
            <label>[{{key}}]
                <textarea v-if="isType(key, 'textarea')" v-model="flatFields[key]" rows="5"></textarea>
                <input type="text" v-if="isType(key, 'text')" v-model="flatFields[key]"/>
                <div v-if="isType(key, 'editor')" :data-key="key" class="summernote"></div>
            </label>
        </div>


        <div class="success callout margin-bottom-1" data-closable="slide-out-right" v-if="success">
            <p>Changes Saved</p>
        </div>
        <div class="alert callout margin-bottom-1" data-closable="slide-out-right" v-if="error">
            <p>Error - Unable To Save</p>
        </div>
        <div class="buttons">
            <button type="button" class="button hollow rounded" @click="save">Save Changes</button>
            <a v-if="url" target="_blank" :href="url" class="button hollow default rounded">View Page</a>
        </div>
    </div>

</template>

<script>
    import axios from 'axios';
    import 'codemirror/lib/codemirror.css';
    import 'codemirror';
    import 'summernote/dist/font/summernote.woff';
    import 'summernote/dist/summernote-lite.css';
    import 'summernote/dist/summernote-lite';

    function bindSummerNote(selector, opts, values) {
        var defaults = {
                tabsize: 2,
                height : 600
            },
            settings = $.extend({}, defaults, opts || {});

        // $(selector).summernote($.extend({}, defaults, opts || {}));
        $(selector).each(function () {
            var $el = $(this),
                key = $el.attr('data-key');
            $el.summernote(settings);
            $el.summernote('code', values[key] || '');
        });
    }


    export default {
        name   : "cms-viewer",
        props  : {
            get : {
                type: String
            },
            path: {
                type: String
            },
            post: {
                type: String
            },
            url : {
                type: String
            }
        },
        data() {
            let vue = this;

            $.get(this.get, function (response) {
                vue.data       = response;
                var config     = _.pick(vue.data, ['config']);
                vue.config     = config.config || {};
                vue.fields     = _.omit(response, ['config']);
                vue.flatFields = vue.dot(vue.fields);
                vue.fieldKeys  = _.keys(vue.flatFields);
                vue.configKeys = _.keys(vue.config);
                vue.$nextTick(function () {
                    bindSummerNote('.summernote', {}, vue.fields);
                });
            });

            return {
                success   : false,
                error     : false,
                flatFields: {}
            };
        },
        mounted() {

        },
        methods: {
            isType      : function (key, type) {
                return this.configSearch(key) === type;
            },
            configSearch: function (key) {
                var segments    = key.split('.'),
                    filteredKey = _.filter(segments, function (n) {
                        return isNaN(parseInt(n));
                    }).join('.');

                var res = _.find(this.config, function (n, m) {
                    return (new RegExp(m)).test(filteredKey);
                });

                return res || 'text';

            },
            dot         : function (data, prepend) {
                var res = {},
                    key;

                prepend = (prepend || '') ? prepend + '.' : '';

                // console.log(data);
                for (key in data) {
                    if (data.hasOwnProperty(key)) {
                        if (typeof data[key] === 'object') {
                            $.extend(res, this.dot(data[key], prepend + key));
                        }
                        else {
                            res[prepend + key] = data[key];
                        }
                    }
                }

                return res;
            },
            setDot      : function (res, index, value) {
                if (!index) return;
                var keys = index.split('.'),
                    w,
                    ii,
                    jj;

                for (ii = 0; ii < keys.length - 1; ii++) {
                    if (res.hasOwnProperty(keys[ii])) {
                        res = res[keys[ii]];
                    }
                    else {
                        for (jj = keys.length - 1; ii <= jj; jj--) {
                            w               = value;
                            value           = {};
                            value[keys[jj]] = w;
                        }
                        res[keys[ii]] = value[keys[ii]];
                        return;
                    }
                }
                res[keys[ii]] = value;
            },
            save        : function () {
                let vue       = this,
                    fieldsObj = {},
                    data;

                _.each(this.flatFields, function (value, key) {
                    vue.setDot(fieldsObj, key, value);
                });

                $('.summernote').each(function () {
                    var $el = $(this);
                    vue.setDot(fieldsObj, $el.attr('data-key'), $el.summernote('code'));
                });

                data = $.extend({}, vue.data, fieldsObj);

                axios.post(this.post, {
                    title: this.path,
                    data : data
                })
                .then(function (response) {
                    let responseData = response && response.data;

                    if (responseData.ok) {
                        vue.success = true;
                        setTimeout(function () {
                            vue.success = false;
                        }, 3000);
                        return;
                    }

                    vue.error = true;
                    setTimeout(function () {
                        vue.error = false;
                    }, 3000);
                })
                .catch(function (error) {
                    debugger;
                    vue.error = true;
                    setTimeout(function () {
                        vue.error = false;
                    }, 3000);
                });

            }
        }
    };
</script>

<style scoped>

</style>