<template>
    <div>
        <div class="form-group col-sm-6 col-md-6"
             v-for="div in divs"
             :key="div.id">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#" style="text-align:right;" @click="deleteRow(div.id)"><i class="fa fa-remove"></i> Remove</a>
                </div>

                <div class="panel-body">

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label>Section Title *</label>
                            <input type="text" required name="section_title" id="section_title" class="form-control"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <label>Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                            <input type="file" name="file" id="file" value="Upload"/>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-xs-12" style="margin-bottom:20px">
            <button type="button" class="btn btn-dark" @click="addRow">
                <i class="fa fa-plus"></i>
                Add Course Section
            </button>
        </div>
    </div>

</template>

<script>
    export default {
        name: "CreateCourseContent",
        data() {
            return {
                index: 0,
                divs: []
            }
        },
        methods: {
            addRow: function () {
                this.divs.push({
                    id: this.index,
                    name: 'div' + this.index,
                });
                this.index++;
            },
            deleteRow: function (index) {
                this.divs.splice(index, 1);
                this.index--;
            }
        }
    }
</script>

<style scoped>

</style>